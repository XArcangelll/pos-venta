<?php

class Administracion extends Controller{

    public function __construct()
    {
        session_start();
        if(empty($_SESSION["activo"])){
            header("location: ".constant("URL"));

        }
        parent::__construct();
    }

    public function index(){
        $data = $this->model->getEmpresa();
        $this->views->getView($this,"index",$data);
    }

    public function modificar(){
       $ruc = $_POST["ruc"];
       $nombre = $_POST["nombre"];
       $telefono = $_POST["telefono"];
       $direccion = $_POST["direccion"];
       $mensaje = $_POST["mensaje"];
        $id = $_POST["id"];
        if (empty($ruc)|| empty($nombre) || empty($telefono) || empty($direccion) ||empty($mensaje) || empty($id)) {
            $msg = "Todos los campos son obligatorios";
        } else {
                    if (!is_numeric($id)) {
                        $msg = "El ID de la Configuración no es entero";
                    } else {
                        $validarConfiguración = $this->model->getConfiguraciónId($id);
                        if ($validarConfiguración) {
                            $data =  $this->model->modificar($ruc,$nombre,$telefono,$direccion,$mensaje, $id);
                            if ($data == "ok") {
                                $msg = "ok";
                            }else {
                                $msg = "Error al modificar la Configuración";
                            }
                        
                        } else {
                            $msg = "No modifique el ID Configuración";
                        }
                    }
                
             
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


}