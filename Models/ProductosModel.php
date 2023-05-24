<?php

class ProductosModel extends Query
{
        private $id,$codigo,$descripcion,$precio_compra,$precio_venta,$cantidad,$id_medida,$id_categoria,$id_user,$estado;

        public function __construct()
        {
                parent::__construct();
        }

        public function getMedidas()
        {
                $sql = "SELECT * FROM medidas where estado = 1";
                $data = $this->selectAll($sql);
                return $data;
        }

        public function getCategorias()
        {
                $sql = "SELECT * FROM categorias where estado = 1";
                $data = $this->selectAll($sql);
                return $data;
        }

        public function getMedidaId(int $id_medida)
        {
                $sql = "SELECT * FROM medidas where id = $id_medida and estado = 1";
                $data = $this->select($sql);
                return $data;
        }

        public function getCategoriaId(int $id_categoria)
        {
                $sql = "SELECT * FROM categorias where id = $id_categoria and estado = 1";
                $data = $this->select($sql);
                return $data;
        }

        public function getProductoId(int $id)
        {
                $sql = "SELECT * FROM productos where id = $id";
                $data = $this->select($sql);

                return $data;
        }


        public function getProductos()
        {
                $sql = "SELECT p.id, p.codigo, p.descripcion, p.precio_compra, p.precio_venta, p.cantidad, p.estado, m.id as id_medida, m.nombre as nombre_medida, c.id as id_categoria, c.nombre as nombre_categoria FROM productos p inner join medidas m on p.id_medida = m.id inner join categorias c on p.id_categoria = c.id";
                $data = $this->selectAll($sql);
                return $data;
        }

        public function registrarProducto(string $codigo, string $descripcion, float $precio_compra, float $precio_venta, int $cantidad, int $id_medida, int $id_categoria, int $id_user)
        {
                $this->codigo = $codigo;
                $this->descripcion = $descripcion;
                $this->precio_compra = $precio_compra;
                $this->precio_venta = $precio_venta;
                $this->cantidad = $cantidad;
                $this->id_medida = $id_medida;
                $this->id_categoria = $id_categoria;
                $this->id_user = $id_user;
                $verificar = "SELECT * FROM productos where codigo = '$this->codigo'";
                $existe = $this->select($verificar);
                if (empty($existe)) {
                        $sql = "INSERT INTO productos(codigo,descripcion,precio_compra,precio_venta,cantidad,id_medida,id_categoria,id_usuario) VALUES(?,?,?,?,?,?,?,?)";
                        $datos = array($this->codigo,$this->descripcion,$this->precio_compra,$this->precio_venta,$this->cantidad,$this->id_medida,$this->id_categoria,$this->id_user);
                        $data =  $this->save($sql, $datos);
                        if ($data == 1) {
                                $res = "ok";
                        } else {
                                $res = "error";
                        }
                } else {
                        $res = "existe";
                }


                return $res;
        }

        public function modificarProducto(string $codigo, string $descripcion, float $precio_compra, float $precio_venta, int $cantidad, int $id_medida, int $id_categoria, int $id)
        {
            $this->codigo = $codigo;
            $this->descripcion = $descripcion;
            $this->precio_compra = $precio_compra;
            $this->precio_venta = $precio_venta;
            $this->cantidad = $cantidad;
            $this->id_medida = $id_medida;
            $this->id_categoria = $id_categoria;
            $this->id = $id;
                $verificar = "SELECT * FROM productos where codigo = '$this->codigo' and id != $this->id";
                $existe = $this->select($verificar);
                if (empty($existe)) {
                        $sql = "UPDATE productos SET codigo = ?, descripcion = ?, precio_compra = ?, precio_venta = ?, cantidad = ?, id_medida = ?, id_categoria = ? WHERE id = ?";
                        $datos = array($this->codigo,$this->descripcion,$this->precio_compra,$this->precio_venta,$this->cantidad,$this->id_medida,$this->id_categoria,$this->id);
                        $data =  $this->save($sql, $datos);
                        if ($data == 1) {
                                $res = "modificado";
                        } else {
                                $res = "error";
                        }
                } else {
                        $res = "existe";
                }



                return $res;
        }

        public function editarProducto(int $id)
        {
                $sql = "SELECT * FROM productos WHERE id = $id and estado = 1";
                $data = $this->select($sql);
                return $data;
        }

        public function accionProducto(int $estado, int $id)
        {
                $this->id = $id;
                $this->estado = $estado;
                $sql = "UPDATE productos set estado = ? WHERE id = ? ";
                $datos = array($this->estado,$this->id);
                $data = $this->save($sql,$datos);
                return $data;
        }

       
}
