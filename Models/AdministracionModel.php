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

    public function getDatos(string $table)
    {
        $sql = "SELECT COUNT(*) as total from $table";
        $data = $this->select($sql);
        return $data;
    }

    public function getVentas()
    {
        $sql = "SELECT COUNT(*) as total from ventas WHERE fecha > CURDATE()";
        $data = $this->select($sql);
        return $data;
    }

    public function getGananciasHoy()
    {
        $sql = "SELECT SUM(total) as total from ventas WHERE fecha > CURDATE()";
        $data = $this->select($sql);
        return $data;
    }

    public function getStockMinimo(){
        $sql = "SELECT * FROM productos WHERE cantidad < 1000 and id_medida = 2  ORDER BY cantidad DESC LIMIT 10";
        $sql2 = "SELECT * FROM productos WHERE  cantidad < 10 and id_medida != 2 ORDER BY cantidad DESC LIMIT 10";
        $data["productosg"] = $this->selectAll($sql);
        $data["productosu"] = $this->selectAll($sql2);
        return $data;
    }

    public function getProductosVendidos(){
        $sql = "SELECT p.descripcion, SUM(d.cantidad) as total FROM detalle_ventas d INNER JOIN Productos p ON p.id = d.id_producto where p.id_medida = 2 group by d.id_producto order by d.cantidad DESC LIMIT 10";
        $sql2 = "SELECT p.descripcion, SUM(d.cantidad) as total FROM detalle_ventas d INNER JOIN Productos p ON p.id = d.id_producto where p.id_medida != 2 group by d.id_producto order by d.cantidad DESC LIMIT 10";
        $data["productosg"] = $this->selectAll($sql);
        $data["productosu"] = $this->selectAll($sql2);
        return $data;
    }



}
