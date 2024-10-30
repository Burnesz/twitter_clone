<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
    public function timeline()
    {
        $this->sessaoAtiva();

        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);

        $this->view->tweets = $tweet->getAll();

        $usuario = Container::getModel('Usuario');
        
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->render('timeline');

    }

    public function tweet()
    {
        $this->sessaoAtiva();

        $tweet = Container::getModel('Tweet');

        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        if($_POST['tweet'] != ''){
            $tweet->salvar();
        }
        header('Location:/timeline');

    }

    public function sessaoAtiva()
    {
        session_start();

        if(!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '')
        {
            header('Location:/?login=erro');
        }

    }

    public function quemSeguir()
    {
        $this->sessaoAtiva();

        $usuarios = array();

        $usuario = Container::getModel('Usuario');
        
        $usuario->__set('id', $_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_tweets = $usuario->getTotalTweets();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $pesquisarPor = isset($_GET['pesquisarPor'])?$_GET['pesquisarPor']:'';

        if($pesquisarPor != '')
        {
            $usuario->__set('nome', $pesquisarPor);
            $usuarios = $usuario->getAll();
        }

        $this->view->usuarios = $usuarios;

        $this->render('quemSeguir');
    }

    public function acao()
    {
        $this->sessaoAtiva();

        $acao = isset($_GET['acao'])?$_GET['acao']:'';
        $id_usuario = isset($_GET['id_usuario'])?$_GET['id_usuario']:'';

        $usuario_seguidores = Container::getModel('UsuariosSeguidores');
        $usuario_seguidores->__set('id_usuario', $_SESSION['id']);
        $usuario_seguidores->__set('id_usuario_seguindo', $id_usuario);
        
        if($acao == 'seguir')
        {
            $usuario_seguidores->seguir();

        }else if($acao == 'deixar_de_seguir')
        {
            $usuario_seguidores->deixarDeSeguir();
        }

        $pesquisarPor = $_GET['pesquisarPor'];

        header('Location:/quem_seguir?pesquisarPor='.$pesquisarPor);

    }

    public function excluir()
    {
        $this->sessaoAtiva();

        $tweet = Container::getModel('Tweet');

        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->__set('id', $_GET['id_tweet']);
        $tweet->excluir();

        header('Location:/timeline');
    }
}