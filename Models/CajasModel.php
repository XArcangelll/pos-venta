<?php

class CajasModel extends Query
{
    private $id, $caja, $estado;

    public function __construct()
    {
        parent::__construct();
    }



    public function getCajaId(int $id)
    {
        $sql = "SELECT * FROM caja where id = $id";
        $data = $this->select($sql);

        return $data;
    }


    public function getCajas()
    {
        $sql = "SELECT * from caja";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function estadoCaja(int $id_caja){
        $sql = "SELECT cc.* FROM sistema.cierre_caja cc inner join usuarios u on u.id = cc.id_usuario where cc.estado = 1 and u.id_caja = $id_caja";
        $data = $this->select($sql);
        return $data;
    }

    
    public function getArqueo()
    {
        $sql = "SELECT c.*, u.nombre as usuario from cierre_caja c inner join usuarios u on u.id = c.id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarCaja(string $caja)
    {
        $this->caja = $caja;
        $sql = "INSERT INTO caja(caja) VALUES(?)";
        $datos = array($this->caja);
        $data =  $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }



        return $res;
    }

    public function modificarCaja(string $caja, int $id)
    {
     
        $this->caja = $caja;
        $this->id = $id;
            $sql = "UPDATE caja SET caja = ? WHERE id = ?";
            $datos = array($this->caja, $this->id);
            $data =  $this->save($sql, $datos);
            if ($data == 1) {
                $res = "modificado";
            } else {
                $res = "error";
            }




        return $res;
    }

    public function editarCaja(int $id)
    {
        $sql = "SELECT * FROM caja WHERE id = $id and estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function accionCaja(int $estado, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE caja set estado = ? WHERE id = ? ";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function registrarArqueo(int $id_usuario, string $monto_inicial,string $fecha_apertura)
    {
        $caja = "SELECT id_caja from usuarios where id = $id_usuario";
        $datac = $this->select($caja);
        $datacaja = $datac["id_caja"];
        $verificar = "SELECT cc.* FROM cierre_caja cc INNER JOIN usuarios u on u.id = cc.id_usuario  WHERE u.id_caja = $datacaja and cc.estado = 1";
        $existe = $this->select($verificar); 
        if(empty($existe)){
        $sql = "INSERT INTO cierre_caja(id_usuario,monto_inicial,fecha_apertura) VALUES(?,?,?)";
        $datos = array($id_usuario,$monto_inicial,$fecha_apertura);
        $data =  $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        }else{
            $res = "existe";
        }
        return $res;
    }

    public function getVentas(int $id_usuario){
        $sql = "SELECT  SUM(total) AS total FROM ventas WHERE id_usuario = $id_usuario and estado = 1 and apertura = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function getTotalVentas(int $id_usuario){
        $sql = "SELECT COUNT(total) AS total FROM ventas WHERE id_usuario = $id_usuario and estado = 1 and apertura = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function getMontoInicial(int $id_usuario){
        $sql = "SELECT id, monto_inicial,fecha_apertura FROM cierre_caja WHERE id_usuario = $id_usuario and estado = 1 ";
        $data = $this->select($sql);
        return $data;
    }

    public function actualizarArqueo(string $monto_final, string $fecha_cierre,string $ventas, string $general, $id )
    {
       
        $sql = "UPDATE cierre_caja set monto_final = ?,fecha_cierre = ?, total_ventas = ?, monto_total = ?, estado = ? where id = ?";
        $datos = array($monto_final,$fecha_cierre,$ventas,$general,0,$id);
        $data =  $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function actualizarApertura(int $id_usuario )
    {
       
        $sql = "UPDATE ventas SET apertura = ? where id_usuario = ?";
        $datos = array(0,$id_usuario);
        $this->save($sql, $datos);
    }

    public function getIdArqueo(int $id_usuario,int $id){
        $sql = "SELECT * FROM cierre_caja WHERE id_usuario = $id_usuario and id = $id and estado = 1 ";
        $data = $this->select($sql);
        return $data;
    }

    public function verificarPermiso(int $id_user, string $nombre){

        $sql = "SELECT p.id, p.permiso, d.id as id_detalle, d.id_usuario, d.id_permiso FROM permisos p inner join detalle_permisos d ON p.id = d.id_permiso where d.id_usuario = $id_user AND p.permiso = '$nombre'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function estadoCierreCaja(int $id_user){
       $sql =  "select * from cierre_caja where id_usuario = $id_user and estado = 1";
       $data = $this->select($sql);
       return $data;
    }

}
