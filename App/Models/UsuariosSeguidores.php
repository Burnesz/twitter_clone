<?php

namespace App\Models;

use MF\Model\Model;

class UsuariosSeguidores extends Model
{
    private $id;
    private $id_usuario;
    private $id_usuario_seguindo;

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function seguir()
    {
        $query = 'insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values (:id_usuario, :id_usuario_seguindo)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
        $stmt->execute();
    }

    public function deixarDeSeguir()
    {
        $query = 'delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':id_usuario_seguindo', $this->__get('id_usuario_seguindo'));
        $stmt->execute();
    }
}

?>