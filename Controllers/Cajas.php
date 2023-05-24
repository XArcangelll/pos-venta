<?php
class Cajas extends Controller
{

    public function __construct()
    {
        session_start();

        parent::__construct();
    }


    public function index()
    {
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }
        $this->views->getView($this, "index");
    }

    public function listar()
    {

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


    public function registrar()
    {

        $caja = $_POST["caja"];
        $id = $_POST["id"];
        if (empty($caja)) {
            $msg = "Todos los campos son obligatorios";
        } else {

                if ($id == "") {
                        $data =  $this->model->registrarCaja($caja);
                        if ($data == "ok") {
                            $msg = "ok";
                        }else {
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
                            }else {
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

    public function editar(int $id)
    {
        $data = $this->model->editarCaja($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar($id)
    {
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

    public function reingresar($id)
    {
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
