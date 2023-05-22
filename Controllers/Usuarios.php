<?php
class Usuarios extends Controller
{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }


    public function index()
    {
        $data['cajas'] =  $this->model->getCajas();
        $this->views->getView($this, "index", $data);
    }

    public function listar()
    {

        $data = $this->model->getUsuarios();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
            }
            $data[$i]['acciones'] =  '<div><button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_usuario" onclick="btnEditarUser(' . $data[$i]["id"] . ');" class="btn btn-primary mx-2" >Editar</button><button type="button"  class="btn btn-danger" >Eliminar</button></div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function validar()
    {

        if (empty($_POST["usuario"]) || empty($_POST["clave"])) {
            $msg = "Los campos están vacíos";
        } else {
            $usuario = $_POST["usuario"];
            $clave = $_POST["clave"];
            $data = $this->model->getUsuario($usuario, $clave);
            if ($data) {
                $_SESSION["id_usuario"] = $data["id"];
                $_SESSION["usuario"] = $data["usuario"];
                $_SESSION["nombre"] = $data["nombre"];
                $msg = "ok";
            } else {
                $msg = "Usuario y/o Contraseña incorrectos";
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        die();
    }

    public function registrar()
    {

        $usuario = $_POST["usuario"];
        $nombre = $_POST["nombre"];
        $clave = $_POST["clave"];
        $confirmar = $_POST["confirmar"];
        $caja = $_POST["caja"];
        $id = $_POST["id"];
        if (empty($usuario) || empty($nombre)  || empty($caja)) {
            $msg = "Todos los campos son obligatorios";
        } else if (!is_numeric($caja)) {
            $msg = "El id no es entero";
        } else {

            $validar = $this->model->getCajaId($caja);
            if ($validar) {
                if ($id == "") {
                    if (empty($clave)  || empty($confirmar)) {
                        $msg = "Llene las contraseñas";
                    } else if ($clave != $confirmar) {
                        $msg = "Las contraseñas no coinciden we";
                    } else {
                        $encriptado = md5($clave);
                        $data =  $this->model->registrarUsuario($usuario, $nombre, $encriptado, $caja);
                        if ($data == "ok") {
                            $msg = "ok";
                        } else if ($data == "existe") {
                            $msg = "El usuario ya existe";
                        } else {
                            $msg = "Error al registrar el usuario";
                        }
                    }
                } else {

                    if(!is_numeric($id)) {
                        $msg = "El ID Usuario no es entero";
                    }else{
                        $validarUsuario = $this->model->getUsuarioId($id);
                        if ($validarUsuario) {
                            $data =  $this->model->modificarUsuario($usuario, $nombre, $caja, $id);
                            if ($data == "modificado") {
                                $msg = "modificado";
                            } else if ($data == "existe") {
                                $msg = "El usuario ya existe";
                            } else {
                                $msg = "Error al modificar el usuario";
                            }
                        } else {
                            $msg = "No modifique el ID Usuario";
                        }
                    }

                   
                }
            } else {
                $msg = "No modifique el id Caja";
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarUser($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}
