<?php

class MedidasModel extends Query
{
    private $id, $nombre,$nombre_corto, $estado;

    public function __construct()
    {
        parent::__construct();
    }



    public function getMedidaId(int $id)
    {
        $sql = "SELECT * FROM medidas where id = $id";
        $data = $this->select($sql);

        return $data;
    }


    public function getMedidas()
    {
        $sql = "SELECT * from medidas";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarMedida(string $nombre,string $nombre_corto)
    {
        $this->nombre = $nombre;
        $this->nombre_corto = $nombre_corto;
        $sql = "INSERT INTO medidas(nombre,nombre_corto) VALUES(?,?)";
        $datos = array($this->nombre,$this->nombre_corto);
        $data =  $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }



        return $res;
    }

    public function modificarMedida(string $nombre,string $nombre_corto, int $id)
    {
     
        $this->nombre = $nombre;
        $this->nombre_corto = $nombre_corto;
        $this->id = $id;
            $sql = "UPDATE medidas SET nombre = ?, nombre_corto = ? WHERE id = ?";
            $datos = array($this->nombre,$this->nombre_corto, $this->id);
            $data =  $this->save($sql, $datos);
            if ($data == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }




        return $res;
    }

    public function editarMedida(int $id)
    {
        $sql = "SELECT * FROM medidas WHERE id = $id and estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function accionMedida(int $estado, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE medidas set estado = ? WHERE id = ? ";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }
}
