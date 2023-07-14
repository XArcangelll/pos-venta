<?php
class Usuarios extends Controller
{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }


    public function validacionRutas(){
        

        if(empty($_SESSION["activo"])){
            header("location: ".constant("URL"));

        }else{

            if(!empty($_SESSION["rol"])){
                
       

            if($_SESSION["rol"] != 1){
                header("location: ".constant("URL")."Clientes");
              }
            }
        }
    
    }


    public function index()
    {

        $this->validacionRutas();

        if(isset($_SESSION["id_usuario"])){
            $id_user = $_SESSION["id_usuario"];
            $verificar = $this->model->verificarPermiso($id_user,'usuarios');  
            
            if(!empty($verificar) || $id_user == 1){
                $data['cajas'] =  $this->model->getCajas();
                $this->views->getView($this, "index", $data);
            }else{
                
                header("location: ".constant("URL")."Errors/permisos");
            }
        }else{
            header("location: ".constant("URL"));
        }

      

    
    }

    public function listar()
    {

        $this->validacionRutas();
        $data = $this->model->getUsuarios();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
                if($data[$i]["id_rol"] == 1){
                    $data[$i]['acciones'] =  '<div>
                    <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-key"></i></button>
                    <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-edit"></i></button>
                    <button type="button"  class="btn btn-secondary disabled" ><i class="fas fa-trash"></i></button>
                    <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                    </div>';
                }else{
                    
                $data[$i]['acciones'] =  '<div>
                <a href="' . constant("URL") . 'Usuarios/permisos/' . $data[$i]["id"] . '" class="btn btn-dark ms-2" ><i class="fas fa-key"></i></a>
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_usuario" onclick="btnEditarUser(' . $data[$i]["id"] . ');" class="btn btn-primary mx-2" ><i class="fas fa-edit"></i></button>
                <button type="button" onclick="btnEliminarUser(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-trash"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                </div>';
                }
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-key"></i></button>
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
                $_SESSION["rol"] = $data["id_rol"];
                $_SESSION["activo"] = true;
                $msg = array("msg" => "ok", "id_rol" => $_SESSION["rol"]);
            } else {
                $msg =  array("msg" => "Usuario y/o Contraseña incorrectos");
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        die();
    }

    public function registrar()
    {


        $this->validacionRutas();

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
                        $data =  $this->model->registrarUsuario($usuario, $nombre, $encriptado,2, $caja);
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

        $this->validacionRutas();

        $data = $this->model->editarUser($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar($id)
    {

        $this->validacionRutas();

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


        $this->validacionRutas();

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

    function cambiarPass()
    {
        if(empty($_SESSION["activo"])){
            header("location: ".constant("URL"));

        }
        $actual = $_POST["clave_actual"];
        $nueva = $_POST["clave_nueva"];
        $confirmar = $_POST["confirmar_clave"];
        if (empty($actual) || empty($nueva) || empty($confirmar)) {
            $msg = "Todos los Campos son obligatorios";
        } else {

            if ($nueva != $confirmar) {
                $msg = "Las contraseñas no coinciden";
            } else {
                $id = $_SESSION["id_usuario"];
                $data = $this->model->editarUser($id);
                if (!empty($data)) {

                    if (md5($actual) == $data["clave"]) {
                        $verificar = $this->model->modificarPass(md5($nueva), $id);
                        if ($verificar == 1) {
                            $msg = "ok";
                        } else {
                            $msg = "Error al modificar la contraseña";
                        }
                    } else {
                        $msg = "La contraseña actual incorrecta";
                    }
                } else {
                    $msg = "No existen datos del usuario";
                }
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }



    public function salir()
    {
        session_destroy();
        header("location: " . constant("URL"));
    }

    public function permisos($id)
    {
        $this->validacionRutas();

        if(!is_numeric($id) || $id == ""){
            header("location: " . constant("URL")."Usuarios");
        }else{

         $verificar =  $this->model->getUsuarioIdDetallePermiso($id);
         if(!empty($verificar)){
                
        $data["datos"] =  $this->model->getPermisos();
        $permisos = $this->model->getDetallePermisos($id);
        $data["asignados"] = array();
        foreach($permisos as $permiso){
            $data["asignados"][$permiso["id_permiso"]] = true;
        }
        $data["id_usuario"] = $id;
        $this->views->getView($this, "permisos", $data);   
         }else{
            header("location: " . constant("URL")."Usuarios");
         }

        }

    }

    public function registrarPermiso()
    {

        $this->validacionRutas();

        $msg = "";
        $id_user = $_POST["id"];
        $eliminar = $this->model->eliminarPermisos($id_user);
        if ($eliminar == "ok") {

            if(!empty($_POST["permisos"])){
                    
            foreach ($_POST["permisos"] as $id_permiso) {
                $msg = $this->model->registrarPermisos($id_user, $id_permiso);
             }
 
             if($msg == "ok"){
                 $msg = array("msg"=> "Permisos asignados","icono"=>"success");
             }else{
                 $msg = array("msg"=> "Error al asignar los permisos","icono"=>"error");
             }
            }else{
                $msg = array("msg"=> "Eliminación de todos los permisos asignados","icono"=>"success");
            }


        }else{
            $msg = array("msg"=> "Error al eliminar los permisos anteriores","icono"=>"error");
        }
        echo json_encode($msg,JSON_UNESCAPED_UNICODE);
        die();
    }
}
