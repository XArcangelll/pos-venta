<?php

class ClientesModel extends Query
{
        private $dni, $nombre, $telefono, $direccion, $id,$estado, $iduser;

        public function __construct()
        {
                parent::__construct();
        }

        

        public function getClienteId(int $id)
        {
                $sql = "SELECT * FROM clientes where id = $id";
                $data = $this->select($sql);

                return $data;
        }


        public function getClientes()
        {
                $sql = "SELECT * from clientes";
                $data = $this->selectAll($sql);
                return $data;
        }

        public function validarDNI(string $dni){
                $this->dni = $dni;
                if(!is_numeric($this->dni)){
                    return false;
                }else if(strlen($this->dni) != 8 ){
                    return false;
                }else{
                    return true;
                }
        }

        public function registrarCliente(string $dni, string $nombre, string $telefono, string $direccion, int $iduser)
        {
                $this->dni = $dni;
                $this->nombre = $nombre;
                $this->telefono = $telefono;
                $this->direccion = $direccion;
                $this->iduser = $iduser;
                $verificar = "SELECT * FROM clientes where dni = '$this->dni'";
                $existe = $this->select($verificar);
                if (empty($existe)) {
                        $sql = "INSERT INTO clientes(dni,nombre,telefono,direccion,id_usuario) VALUES(?,?,?,?,?)";
                        $datos = array($this->dni, $this->nombre, $this->telefono, $this->direccion,$this->iduser);
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

        public function modificarCliente(string $dni, string $nombre, string $telefono,string $direccion, int $id)
        {
                $this->dni = $dni;
                $this->nombre = $nombre;
                $this->id = $id;
                $this->telefono = $telefono;
                $this->direccion = $direccion;
                $verificar = "SELECT * FROM clientes where dni = '$this->dni' and id != $this->id";
                $existe = $this->select($verificar);
                if (empty($existe)) {
                        $sql = "UPDATE clientes SET dni = ?, nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
                        $datos = array($this->dni, $this->nombre, $this->telefono,$this->direccion, $this->id);
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

        public function editarCliente(int $id)
        {
                $sql = "SELECT * FROM clientes WHERE id = $id and estado = 1";
                $data = $this->select($sql);
                return $data;
        }

        public function accionCliente(int $estado, int $id)
        {
                $this->id = $id;
                $this->estado = $estado;
                $sql = "UPDATE clientes set estado = ? WHERE id = ? ";
                $datos = array($this->estado,$this->id);
                $data = $this->save($sql,$datos);
                return $data;
        }


        public function verificarPermiso(int $id_user, string $nombre){

                $sql = "SELECT p.id, p.permiso, d.id as id_detalle, d.id_usuario, d.id_permiso FROM permisos p inner join detalle_permisos d ON p.id = d.id_permiso where d.id_usuario = $id_user AND p.permiso = '$nombre'";
                $data = $this->selectAll($sql);
                return $data;
            }

       
}
