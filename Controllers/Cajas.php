<?php
class Cajas extends Controller
{

    public function __construct()
    {
        session_start();
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }
        parent::__construct();
    }


    public function index()
    {
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }else{
            if($_SESSION["rol"] != 1){
                header("location: ".constant("URL")."Clientes");
              }
        }
        $this->views->getView($this, "index");
    }

    public function arqueo()
    {

        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }else{
        $id_user = $_SESSION["id_usuario"];
        $verificar = $this->model->verificarPermiso($id_user,'arqueo_caja');  
        if(!empty($verificar) || $id_user == 1){
            $this->views->getView($this, "arqueo");
        }else{
            
            header("location: ".constant("URL")."Errors/permisos");
        }
    }

        
   
    }

    public function listar()
    {

        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }else{


        if($_SESSION["rol"] != 1){
            header("location: ".constant("URL")."Clientes");
          }else{

        $data = $this->model->getCajas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_caja" onclick="btnEditarCaja(' . $data[$i]["id"] . ');" class="btn btn-primary mx-2" ><i class="fas fa-edit"></i></button>
                <button type="button" onclick="btnEliminarCaja(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-trash"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-edit"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fas fa-trash"></i></button>
                <button type="button" onclick="btnReingresarCaja(' . $data[$i]["id"] . ');" class="btn btn-success" ><i class="fa fa-rotate"></i></button>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    }
    }


    public function registrar()
    {

      


        if($_SESSION["rol"] != 1){
            header("location: ".constant("URL")."Clientes");
          }else{


        $caja = $_POST["caja"];
        $id = $_POST["id"];
        if (empty($caja)) {
            $msg = "Todos los campos son obligatorios";
        } else {

            if ($id == "") {
                $data =  $this->model->registrarCaja($caja);
                if ($data == "ok") {
                    $msg = "ok";
                } else {
                    $msg = "Error al registrar la caja";
                }
            } else {

                if (!is_numeric($id)) {
                    $msg = "El ID de la caja no es entero";
                } else {
                    $validarCaja = $this->model->getCajaId($id);
                    if ($validarCaja) {
                        $data =  $this->model->modificarCaja($caja, $id);
                        if ($data == "modificado") {
                            $msg = "modificado";
                        } else {
                            $msg = "Error al modificar la caja";
                        }
                    } else {
                        $msg = "No modifique el ID Caja";
                    }
                }
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();

    }
    }

    public function editar(int $id)
    {

        if($_SESSION["rol"] != 1){
            header("location: ".constant("URL")."Clientes");
          }else{


        $data = $this->model->editarCaja($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
          }
    }

    public function eliminar($id)
    {

        if($_SESSION["rol"] != 1){
            header("location: ".constant("URL")."Clientes");
          }else{


        if (!is_numeric($id)) {
            $msg = "El ID Caja no es entero";
        } else {
            $validarCaja = $this->model->getCajaId($id);
            if ($validarCaja) {
                $data = $this->model->accionCaja(0, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al eliminar la Caja";
                }
            } else {
                $msg = "El ID Caja no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    }

    public function reingresar($id)
    {

        if($_SESSION["rol"] != 1){
            header("location: ".constant("URL")."Clientes");
          }else{


        if (!is_numeric($id)) {
            $msg = "El ID Caja no es entero";
        } else {
            $validarCaja = $this->model->getCajaId($id);
            if ($validarCaja) {
                $data = $this->model->accionCaja(1, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al reingresar Caja";
                }
            } else {
                $msg = "El ID Caja no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();

    }
    }



    public function abrirArqueo()
    {
        if(!empty($_POST["monto_inicial"])){
            $monto_inicial = $_POST["monto_inicial"];
        }else{
            $monto_inicial = 0;
        }
        $fecha_apertura = date("Y-m-d");
        $id_usuario = $_SESSION["id_usuario"];
        $id = $_POST["id"];
            if ($id == "") {
                if ($monto_inicial < 0) {
                    $msg = "Todos los campos son obligatorios";
                }else{

                $data = $this->model->registrarArqueo($id_usuario, $monto_inicial, $fecha_apertura);
                if ($data == "ok") {
                    $msg = "ok";
                } else if ($data == "existe") {
                    $msg = "existe";
                } else {
                    $msg = "Hubo un error al abrir la caja";
                }
            }
            } else {

                $verificar = $this->model->getIdArqueo($id_usuario,$id);
                if($verificar){
                    
                $monto_final = $this->model->getVentas($id_usuario);
                if($monto_final["total"]){
                    $montoFinal = $monto_final["total"];
                }else{
                    $montoFinal = 0;
                }
                $total_ventas = $this->model->getTotalVentas($id_usuario);
                $inicial = $this->model->getMontoInicial($id_usuario);
                $general = $monto_final["total"] + $inicial["monto_inicial"];
                $data = $this->model->actualizarArqueo($montoFinal, $fecha_apertura,$total_ventas["total"], $general, $inicial["id"]);
                if ($data == "ok") {
                    $this->model->actualizarApertura($id_usuario);
                    $msg = "oka";
                } else {
                    $msg = "errora";
                }
                }else{
                    $msg = "No modifique el ID o no existe";
                }

            }
        

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }



    public function listarArqueo()
    {

        $data = $this->model->getArqueo();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Abierta</span></h5>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Cerrada</span></h5>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function getVentas()
    {
        $id_usuario = $_SESSION["id_usuario"];
        $data["monto_total"] = $this->model->getVentas($id_usuario);
        $data["total_ventas"] = $this->model->getTotalVentas($id_usuario);
        $data["inicial"] = $this->model->getMontoInicial($id_usuario);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}
