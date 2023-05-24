<?php

class CategoriasModel extends Query
{
    private $id, $nombre, $estado;

    public function __construct()
    {
        parent::__construct();
    }



    public function getCategoriaId(int $id)
    {
        $sql = "SELECT * FROM categorias where id = $id";
        $data = $this->select($sql);

        return $data;
    }


    public function getCategorias()
    {
        $sql = "SELECT * from categorias";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarCategoria(string $nombre)
    {
        $this->nombre = $nombre;
        $sql = "INSERT INTO categorias(nombre) VALUES(?)";
        $datos = array($this->nombre);
        $data =  $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }



        return $res;
    }

    public function modificarCategoria(string $nombre, int $id)
    {
     
        $this->nombre = $nombre;
        $this->id = $id;
            $sql = "UPDATE categorias SET nombre = ? WHERE id = ?";
            $datos = array($this->nombre, $this->id);
            $data =  $this->save($sql, $datos);
            if ($data == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }




        return $res;
    }

    public function editarCategoria(int $id)
    {
        $sql = "SELECT * FROM categorias WHERE id = $id and estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function accionCategoria(int $estado, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE categorias set estado = ? WHERE id = ? ";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }
}
