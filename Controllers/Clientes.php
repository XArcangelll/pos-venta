<?php
class Clientes extends Controller
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
        if(empty($_SESSION["activo"])){
            header("location: ".constant("URL"));
        }else{

            $this->views->getView($this, "index");
}
   
    }

    public function listar()
    {

        $data = $this->model->getClientes();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_cliente" onclick="btnEditarCliente(' . $data[$i]["id"] . ');" class="btn btn-primary mx-2" ><i class="fas fa-edit"></i></button>
                <button type="button" onclick="btnEliminarCliente(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-trash"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-edit"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fas fa-trash"></i></button>
                <button type="button" onclick="btnReingresarCliente(' . $data[$i]["id"] . ');" class="btn btn-success" ><i class="fa fa-rotate"></i></button>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function registrar()
    {


        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }else{
            $id_user = $_SESSION["id_usuario"];
            $verificar = $this->model->verificarPermiso($id_user,'registrar_cliente');  
            if(!empty($verificar) || $id_user == 1){
                $dni = $_POST["dni"];
                $nombre = $_POST["nombre"];
                $telefono = $_POST["telefono"];
                $direccion = $_POST["direccion"];
                $id = $_POST["id"];
                if (empty($dni) || empty($nombre)  || empty($telefono) || empty($direccion)) {
                    $msg = "Todos los campos son obligatorios";
                } else {
        
                        if ($id == "") {
        
                            if(!$this->model->validarDNI($dni)){
                                $msg = "El DNI debe ser entero y de 8 dígitos ";
                            }else{
                                $data =  $this->model->registrarCliente($dni, $nombre, $telefono, $direccion,$_SESSION["id_usuario"]);
                                if ($data == "ok") {
                                    $msg = "ok";
                                } else if ($data == "existe") {
                                    $msg = "El DNI ya existe";
                                } else {
                                    $msg = "Error al registrar el cliente";
                                }
                            }
                            
                        } else {
        
                            if (!is_numeric($id)) {
                                $msg = "El ID cliente no es entero";
                            } else {
                                $validarCliente = $this->model->getClienteId($id);
                                if ($validarCliente) {
                                    if(!$this->model->validarDNI($dni)){
                                        $msg = "El DNI debe ser entero y de 8 dígitos ";
                                    }else{
                                    $data =  $this->model->modificarCliente($dni, $nombre, $telefono,$direccion, $id);
                                    if ($data == "modificado") {
                                        $msg = "modificado";
                                    } else if ($data == "existe") {
                                        $msg = "El cliente ya existe";
                                    } else {
                                        $msg = "Error al modificar el cliente";
                                    }
                                }
                                } else {
                                    $msg = "No modifique el ID cliente";
                                }
                            }
                        }
                     
                }
            }else{
                
                $msg = "Usted no tiene permisos para registrar Cliente";
            }
        }



       

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarCliente($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar($id)
    {


        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }else{
            $id_user = $_SESSION["id_usuario"];
            $verificar = $this->model->verificarPermiso($id_user,'eliminar_cliente');  
            if(!empty($verificar) || $id_user == 1){

                if (!is_numeric($id)) {
                    $msg = "El ID Cliente no es entero";
                } else {
                    $validarCliente = $this->model->getClienteId($id);
                    if ($validarCliente) {
                        $data = $this->model->accionCliente(0, $id);
                        if ($data == 1) {
                            $msg = "ok";
                        } else {
                            $msg = "Error al eliminar el Cliente";
                        }
                    } else {
                        $msg = "El ID del cliente no existe";
                    }
                }

        }else{
            $msg = "Usted no tiene permisos para eliminar clientes";
        }

    }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar($id)
    {

        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }else{
            $id_user = $_SESSION["id_usuario"];
            $verificar = $this->model->verificarPermiso($id_user,'eliminar_cliente');  
            if(!empty($verificar) || $id_user == 1){


        if (!is_numeric($id)) {
            $msg = "El ID Cliente no es entero";
        } else {
            $validarCliente = $this->model->getClienteId($id);
            if ($validarCliente) {
                $data = $this->model->accionCliente(1, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al reingresar el Cliente";
                }
            } else {
                $msg = "El ID del cliente no existe";
            }
        }

    }else{
        $msg = "Usted no tiene permisos para reingresar clientes";
    }

}

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

}
