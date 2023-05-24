<?php
class Medidas extends Controller
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

        $data = $this->model->getMedidas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_medida" onclick="btnEditarMedida(' . $data[$i]["id"] . ');" class="btn btn-primary mx-2" ><i class="fas fa-edit"></i></button>
                <button type="button" onclick="btnEliminarMedida(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-trash"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-edit"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fas fa-trash"></i></button>
                <button type="button" onclick="btnReingresarMedida(' . $data[$i]["id"] . ');" class="btn btn-success" ><i class="fa fa-rotate"></i></button>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function registrar()
    {

        $nombre = $_POST["nombre"];
        $nombre_corto = $_POST["nombre_corto"];
        $id = $_POST["id"];
        if (empty($nombre)) {
            $msg = "Todos los campos son obligatorios";
        } else {

                if ($id == "") {
                        $data =  $this->model->registrarMedida($nombre,$nombre_corto);
                        if ($data == "ok") {
                            $msg = "ok";
                        }else {
                            $msg = "Error al registrar la medida";
                        }
                    
                    
                } else {

                    if (!is_numeric($id)) {
                        $msg = "El ID de la medida no es entero";
                    } else {
                        $validarMedida = $this->model->getMedidaId($id);
                        if ($validarMedida) {
                            $data =  $this->model->modificarMedida($nombre,$nombre_corto,$id);
                            if ($data == "modificado") {
                                $msg = "modificado";
                            }else {
                                $msg = "Error al modificar la medida";
                            }
                        
                        } else {
                            $msg = "No modifique el ID medida";
                        }
                    }
                }
             
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarMedida($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Medida no es entero";
        } else {
            $validarMedida = $this->model->getMedidaId($id);
            if ($validarMedida) {
                $data = $this->model->accionMedida(0, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al eliminar la Medida";
                }
            } else {
                $msg = "El ID Medida no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Medida no es entero";
        } else {
            $validarMedida = $this->model->getMedidaId($id);
            if ($validarMedida) {
                $data = $this->model->accionMedida(1, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al reingresar Medida";
                }
            } else {
                $msg = "El ID Medida no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

}
