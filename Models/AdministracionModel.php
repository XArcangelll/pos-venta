<?php

class AdministracionModel extends Query
{


    public function __construct()
    {
        parent::__construct();
    }


    public function getEmpresa()
    {
        $sql = "SELECT * from configuracion";
        $data = $this->select($sql);
        return $data;
    }

    public function getConfiguraciónId(int $id){
        $sql = "SELECT * FROM configuracion where id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function modificar(string $ruc,string $nombre, string $telefono, string $direccion,string $mensaje, int $id){
        $sql = "UPDATE configuracion set ruc = ?, nombre = ?, telefono = ?, direccion = ?, mensaje = ? WHERE id = ?";
        $datos = array($ruc,$nombre,$telefono,$direccion,$mensaje,$id);
        $data = $this->save($sql,$datos);
        if($data == 1){
            $res = "ok";
        }else{  
            $res = "error";
        }
        return $res;
    }

}
