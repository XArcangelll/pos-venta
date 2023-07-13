<?php
class Categorias extends Controller
{

    public function __construct()
    {
        session_start();
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }else{
            if($_SESSION["rol"] != 1){
                header("location: ".constant("URL")."Clientes");
              }
        }
        parent::__construct();
    }


    public function index()
    {
       
        $this->views->getView($this, "index");
    }

    public function listar()
    {

        $data = $this->model->getCategorias();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_categoria" onclick="btnEditarCategoria(' . $data[$i]["id"] . ');" class="btn btn-primary mx-2" ><i class="fas fa-edit"></i></button>
                <button type="button" onclick="btnEliminarCategoria(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-trash"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-edit"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fas fa-trash"></i></button>
                <button type="button" onclick="btnReingresarCategoria(' . $data[$i]["id"] . ');" class="btn btn-success" ><i class="fa fa-rotate"></i></button>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function registrar()
    {

        $nombre = $_POST["nombre"];
        $id = $_POST["id"];
        if (empty($nombre)) {
            $msg = "Todos los campos son obligatorios";
        } else {

                if ($id == "") {
                        $data =  $this->model->registrarCategoria($nombre);
                        if ($data == "ok") {
                            $msg = "ok";
                        }else {
                            $msg = "Error al registrar la categoría";
                        }
                    
                    
                } else {

                    if (!is_numeric($id)) {
                        $msg = "El ID de la categoría no es entero";
                    } else {
                        $validarCategoria = $this->model->getCategoriaId($id);
                        if ($validarCategoria) {
                            $data =  $this->model->modificarCategoria($nombre, $id);
                            if ($data == "modificado") {
                                $msg = "modificado";
                            }else {
                                $msg = "Error al modificar la categoría";
                            }
                        
                        } else {
                            $msg = "No modifique el ID categoría";
                        }
                    }
                }
             
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarCategoria($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Categoría no es entero";
        } else {
            $validarCategoria = $this->model->getCategoriaId($id);
            if ($validarCategoria) {
                $data = $this->model->accionCategoria(0, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al eliminar la Categoría";
                }
            } else {
                $msg = "El ID Categoría no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Categoría no es entero";
        } else {
            $validarCategoria = $this->model->getCategoriaId($id);
            if ($validarCategoria) {
                $data = $this->model->accionCategoria(1, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al reingresar Categoría";
                }
            } else {
                $msg = "El ID Categoría no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

}
