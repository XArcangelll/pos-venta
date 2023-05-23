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
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }
        $data['cajas'] =  $this->model->getCajas();
        $this->views->getView($this, "index", $data);
    }

    public function listar()
    {

        $data = $this->model->getUsuarios();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_usuario" onclick="btnEditarUser(' . $data[$i]["id"] . ');" class="btn btn-primary mx-2" ><i class="fas fa-edit"></i></button>
                <button type="button" onclick="btnEliminarUser(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-trash"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-edit"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fas fa-trash"></i></button>
                <button type="button" onclick="btnReingresarUser(' . $data[$i]["id"] . ');" class="btn btn-success" ><i class="fa fa-rotate"></i></button>
                </div>';
            }
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
                $_SESSION["activo"] = true;
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

                    if (!is_numeric($id)) {
                        $msg = "El ID Usuario no es entero";
                    } else {
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

    public function eliminar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Usuario no es entero";
        } else {
            $validarUsuario = $this->model->getUsuarioId($id);
            if ($validarUsuario) {
                $data = $this->model->accionUser(0, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al eliminar el Usuario";
                }
            } else {
                $msg = "El ID del usuario no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Usuario no es entero";
        } else {
            $validarUsuario = $this->model->getUsuarioId($id);
            if ($validarUsuario) {
                $data = $this->model->accionUser(1, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al reingresar el Usuario";
                }
            } else {
                $msg = "El ID del usuario no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir(){
        session_destroy();
        header("location: ".constant("URL"));
    }
}
