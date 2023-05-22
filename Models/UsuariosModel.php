<?php

class UsuariosModel extends Query
{       
        private $usuario, $nombre, $clave, $id_caja,$id;

        public function __construct()
        {
                parent::__construct();
        }

        public function getUsuario(string $usuario, string $clave)
        {
                $encriptado = md5($clave);
                $sql = "SELECT * FROM Usuarios WHERE usuario = '$usuario' and clave = '$encriptado'";
                $data = $this->select($sql);
                return $data;
        }

        public function getCajas()
        {
                $sql = "SELECT * FROM caja where estado = 1";
                $data = $this->selectAll($sql);
                return $data;
        }

        public function getCajaId(int $id_caja)
        {
                $sql = "SELECT * FROM caja where id = $id_caja and estado = 1";
                $data = $this->select($sql);
                return $data;
        }

        public function getUsuarioId(int $id)
        {
                $sql = "SELECT * FROM usuarios where id = $id and estado = 1";
                $data = $this->select($sql);
                return $data;
        }


        public function getUsuarios()
        {
                $sql = "SELECT u.id, u.usuario, u.nombre, u.id_rol, u.estado, c.id as id_caja, c.caja FROM usuarios u inner join caja c on u.id_caja = c.id";
                $data = $this->selectAll($sql);
                return $data;
        }

        public function registrarUsuario(string $usuario, string $nombre, string $clave, int $id_caja)
        {
                $this->usuario = $usuario;
                $this->nombre = $nombre;
                $this->clave = $clave;
                $this->id_caja = $id_caja;
                $verificar = "SELECT * FROM usuarios where usuario = '$this->usuario'";
                $existe = $this->select($verificar);
                if(empty($existe)){
                $sql = "INSERT INTO usuarios(usuario,nombre,clave,id_caja) VALUES(?,?,?,?)";
                $datos = array($this->usuario,$this->nombre,$this->clave,$this->id_caja);
                $data =  $this->save($sql,$datos);
                if($data == 1){
                        $res = "ok";
                }else{
                        $res = "error";
                }
                }else{
                        $res = "existe";
                }
                

                return $res;
        }

        public function modificarUsuario(string $usuario, string $nombre, int $id_caja, int $id)
        {
                $this->usuario = $usuario;
                $this->nombre = $nombre;
                $this->id = $id;
                $this->id_caja = $id_caja;
                $verificar = "SELECT * FROM usuarios where usuario = '$this->usuario' and id != $this->id";
                $existe = $this->select($verificar);
                if(empty($existe)){
                $sql = "UPDATE usuarios SET usuario = ?, nombre = ?, id_caja = ? WHERE id = ?";
                $datos = array($this->usuario,$this->nombre,$this->id_caja,$this->id);
                $data =  $this->save($sql,$datos);
                if($data == 1){
                        $res = "modificado";
                }else{
                        $res = "error";
                } }else{
                        $res = "existe";
                }
              
                

                return $res;
        }

        public function editarUser(int $id){
                $sql = "SELECT * FROM usuarios WHERE id = $id";
                $data = $this->select($sql);
                return $data;
        }
}
