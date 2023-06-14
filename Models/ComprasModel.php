<?php

class ComprasModel extends Query
{
    private $id, $nombre, $estado;

    public function __construct()
    {
        parent::__construct();
    }



    public function getProductoCod(string $cod)
    {
        $sql = "SELECT * FROM productos where codigo = '$cod'";
        $data = $this->select($sql);

        return $data;
    }

    public function getClienteid(int $id)
    {
        $sql = "SELECT * FROM clientes where id = $id";
        $data = $this->select($sql);

        return $data;
    }

    public function getProductos()
    {
        $sql = "SELECT p.id, p.codigo, p.descripcion,p.precio_venta, p.precio_compra, p.cantidad FROM productos p where p.estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getClientes()
    {
        $sql = "SELECT * from clientes where estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProductosStock(int $id)
    {
        $sql = "SELECT cantidad FROM productos where id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getProductoId(int $id)
    {
        $sql = "SELECT * FROM productos where id = $id";
        $data = $this->select($sql);

        return $data;
    }

    public function getProductoDetalleVenta(int $id_producto,int $id_usuario){
        $sql = "SELECT * FROM detalle_temp_venta where id_producto = $id_producto and $id_usuario = $id_usuario";
        $data = $this->select($sql);

        return $data;

    }

    public function registrarDetalleTemp(int $id_producto, int $id_usuario, float $precio, int $cantidad, float $sub_total)
    {

        $sql = "INSERT INTO detalle_temp(id_producto,id_usuario,precio,cantidad,sub_total) VALUES(?,?,?,?,?)";
        $datos = array($id_producto, $id_usuario, $precio, $cantidad, $sub_total);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function registrarDetalleTempVenta(int $id_producto, int $id_usuario, float $precio, int $cantidad, float $sub_total)
    {

        $sql = "INSERT INTO detalle_temp_venta(id_producto,id_usuario,precio,cantidad,sub_total) VALUES(?,?,?,?,?)";
        $datos = array($id_producto, $id_usuario, $precio, $cantidad, $sub_total);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function actualizarDetalleTempVenta( float $precio, int $cantidad, float $sub_total,int $id_producto, int $id_usuario)
    {

        $sql = "UPDATE detalle_temp_venta SET precio = ?, cantidad = ?, sub_total = ? WHERE id_producto = ? and id_usuario = ?";
        $datos = array($precio, $cantidad, $sub_total, $id_producto, $id_usuario);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function getDetalleTemporal(int $id)
    {
        $sql = "SELECT dt.*, p.descripcion FROM detalle_temp dt INNER JOIN productos p on p.id = dt.id_producto WHERE dt.id_usuario = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getDetalleTemporalVenta(int $id)
    {
        $sql = "SELECT dt.*, p.descripcion FROM detalle_temp_venta dt INNER JOIN productos p on p.id = dt.id_producto WHERE dt.id_usuario = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function calcularCompra(int $id)
    {
        $sql = "SELECT SUM(sub_total) as total FROM detalle_temp WHERE id_usuario = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function calcularVenta(int $id)
    {
        $sql = "SELECT SUM(sub_total) as total FROM detalle_temp_venta WHERE id_usuario = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function deleteDetalle(int $id, int $id_usuario)
    {
        $sql = "DELETE FROM detalle_temp where id = ? and id_usuario = ?";
        $datos = array($id, $id_usuario);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function deleteDetalleVenta(int $id, int $id_usuario)
    {
        $sql = "DELETE FROM detalle_temp_venta where id = ? and id_usuario = ?";
        $datos = array($id, $id_usuario);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function consultarDetalleTemporal(int $id_producto, int $id_usuario)
    {
        $sql = "SELECT * FROM detalle_temp where id_producto = $id_producto and id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function consultarDetalleTemporalVenta(int $id_producto, int $id_usuario)
    {
        $sql = "SELECT * FROM detalle_temp_venta where id_producto = $id_producto and id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function registraCompra(int $id_usuario,float $total){
        $sql = "INSERT INTO compras(id_usuario,total) VALUES(?,?)";
        $datos = array($id_usuario, $total);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    
    public function registraVenta(int $id_usuario,int $id_cliente,float $total){
        $sql = "INSERT INTO ventas(id_usuario,id_cliente,total) VALUES(?,?,?)";
        $datos = array($id_usuario,$id_cliente, $total);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function id_compra(int $id_usuario){
        $sql = "SELECT MAX(id) as id FROM compras where id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function id_venta(int $id_usuario){
        $sql = "SELECT MAX(id) as id FROM ventas where id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarDetalleCompra(int $id_compra,int $id_producto, int $cantidad,float $precio,float $sub_total){
        $sql = "INSERT INTO detalle_compras(id_compra,id_producto,cantidad,precio,sub_total) VALUES(?,?,?,?,?)";
        $datos = array($id_compra,$id_producto,$cantidad,$precio,$sub_total);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function registrarDetalleVenta(int $id_venta,int $id_producto, int $cantidad,float $precio,float $sub_total){
        $sql = "INSERT INTO detalle_ventas(id_venta,id_producto,cantidad,precio,sub_total) VALUES(?,?,?,?,?)";
        $datos = array($id_venta,$id_producto,$cantidad,$precio,$sub_total);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function getEmpresa(){
        $sql = "SELECT * FROM configuracion";
        $data = $this->select($sql);
        return $data;
    }

    public function vaciarDetalleTemp(int $id_usuario){
        $sql = "DELETE FROM detalle_temp where id_usuario = ?";
        $datos = array($id_usuario);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function getClienteVenta($id_venta){
        $sql = "SELECT v.estado,c.dni,c.nombre FROM ventas v INNER JOIN clientes c on c.id = v.id_cliente where v.id = $id_venta";
        $data = $this->select($sql);
        return $data;
    }

    public function vaciarDetalleTempVenta(int $id_usuario){
        $sql = "DELETE FROM detalle_temp_venta where id_usuario = ?";
        $datos = array($id_usuario);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function getProCompra(int $id_compra){
            $sql = "SELECT c.*,dt.id_compra,dt.id_producto,dt.cantidad,dt.precio,dt.sub_total, p.descripcion FROM  compras c INNER JOIN detalle_compras dt on dt.id_compra = c.id INNER JOIN productos p on p.id = dt.id_producto WHERE c.id = $id_compra";
            $data = $this->selectAll($sql);
            return $data;
    }

    public function getProVenta(int $id_venta){
        $sql = "SELECT v.*,dt.id_venta,dt.id_producto,dt.cantidad,dt.precio,dt.sub_total, p.descripcion FROM  ventas v INNER JOIN detalle_ventas dt on dt.id_venta = v.id INNER JOIN productos p on p.id = dt.id_producto WHERE v.id = $id_venta";
        $data = $this->selectAll($sql);
        return $data;
}

    public function getHistorialCompras(){
        $sql = "SELECT c.id,c.fecha,u.nombre,c.total,c.estado FROM compras c inner join usuarios u on u.id = c.id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getHistorialVentas(){
        $sql = "SELECT v.id,v.fecha,c.nombre as cliente, u.nombre as usuario,v.total,v.estado FROM ventas v inner join usuarios u on u.id = v.id_usuario inner join clientes c on c.id = v.id_cliente";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getDetalleVenta(int $id_venta){
        $sql = "SELECT * FROM detalle_ventas where id_venta = $id_venta";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarStock(int $cantidad, int $id)
    {

        $sql = "UPDATE productos set cantidad = ? WHERE id = ?";
        $datos = array($cantidad,$id);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function getVentaId(int $id){
        $sql = "SELECT * FROM ventas where id = $id";
        $data = $this->select($sql);
        return $data;
    }


    public function accionEstadoVenta(int $estado, int $id_venta)
    {
        $sql = "UPDATE ventas set estado = ? WHERE id = ? ";
        $datos = array($estado,$id_venta);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function AnularEstadoVenta(int $estado, int $id_venta){
        $sql = "UPDATE ventas set estado = ? WHERE id = ? and estado != 0";
        $datos = array($estado,$id_venta);
        $data = $this->save($sql, $datos);
        return $data;
    }
}
