<?php
class Productos extends Controller
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
        $data['medidas'] =  $this->model->getMedidas();
        $data['categorias'] =  $this->model->getCategorias();
        $this->views->getView($this, "index", $data);
    }

    public function listar()
    {

        $data = $this->model->getProductos();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['foto'] = '<img width="50px" src="' . constant("URL") . 'Assets/img/' . $data[$i]["foto"] . '">';
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Activo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button" data-bs-toggle="modal" data-bs-target="#nuevo_producto" onclick="btnEditarProducto(' . $data[$i]["id"] . ');" class="btn btn-primary " ><i class="fas fa-edit"></i></button>
                <button type="button" onclick="btnEliminarProducto(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-trash"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fa fa-rotate"></i></button>
                </div>';
            } else {
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Inactivo</span></h5>';
                $data[$i]['acciones'] =  '<div>
                <button type="button"   class="btn btn-secondary mx-2 " disabled><i class="fas fa-edit"></i></button>
                <button type="button"  class="btn btn-secondary disabled" ><i class="fas fa-trash"></i></button>
                <button type="button" onclick="btnReingresarProducto(' . $data[$i]["id"] . ');" class="btn btn-success" ><i class="fa fa-rotate"></i></button>
                </div>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function registrar()
    {
        $codigo = $_POST["codigo"];
        $descripcion = $_POST["descripcion"];
        $precio_compra = $_POST["precio_compra"];
        $precio_venta = $_POST["precio_venta"];
        $adicional = $_POST["adicional"];
        if(!is_numeric($adicional) || $adicional < 0 || empty($adicional)){
            $adicional = 0;
        }
        $medida = $_POST["medida"];
        $categoria = $_POST["categoria"];
        $usuario = $_SESSION["id_usuario"];
        $id = $_POST["id"];
        $foto = $_FILES["foto"];
        $nombre_foto = $foto["name"];
        $url_temp = $foto["tmp_name"];
        if (empty($codigo) || empty($descripcion)  || empty($precio_compra) || empty($precio_venta) || empty($medida) || empty($categoria)) {
            $msg = "Todos los campos son obligatorios";
        } else if (!is_numeric($medida) || !is_numeric($categoria)) {
            $msg = "El id no es entero";
        } else {

            $validarMedida = $this->model->getMedidaId($medida);
            $validarCategoria = $this->model->getCategoriaId($categoria);
            if ($validarMedida && $validarCategoria) {
                if ($id == "") {

                    $imgProducto = "img_producto.png";

                    if ($nombre_foto != "") {
                        $destino = "Assets/img/";
                        $img_nombre = "img_" . md5(date("d-m-Y H:m:s"));
                        $imgProducto = $img_nombre . ".jpg";
                        $src = $destino . $imgProducto;
                    }

                    $data =  $this->model->registrarProducto($codigo, $descripcion, $precio_compra, $precio_venta, $adicional, $medida, $categoria, $usuario, $imgProducto);
                    if ($data == "ok") {
                        if ($nombre_foto != "") {
                            move_uploaded_file($url_temp, $src);
                        }
                        $msg = "ok";
                    } else if ($data == "existe") {
                        $msg = "El código ya existe";
                    } else {
                        $msg = "Error al registrar el producto";
                    }
                } else {

                    if (!is_numeric($id)) {
                        $msg = "El ID Producto no es entero";
                    } else {
                        $validarProducto = $this->model->getProductoId($id);
                        if ($validarProducto) {

                            $imgProducto = $_POST["foto_actual"];
                            $imgRemove = $_POST["foto_remove"];

                            if ($nombre_foto != "") {
                                $destino = "Assets/img/";
                                $img_nombre = "img_" . md5(date("d-m-Y H:m:s"));
                                $imgProducto = $img_nombre . ".jpg";
                                $src = $destino . $imgProducto;
                            } else {
                                if ($imgProducto != $imgRemove) {
                                    $imgProducto = "img_producto.png";
                                }
                            }

                            $data =  $this->model->modificarProducto($codigo, $descripcion, $precio_compra, $precio_venta, $adicional , $medida, $categoria, $imgProducto, $id);
                            if ($data == "modificado") {
                                if (($nombre_foto != "" && ($_POST["foto_actual"] != "img_producto.png")) || ($_POST["foto_actual"] != $_POST["foto_remove"])) {

                                    if(file_exists("Assets/img/" . $_POST["foto_actual"])){
                                        unlink("Assets/img/" . $_POST["foto_actual"]);
                                    }
                                   
                                }

                                if ($nombre_foto != "") {
                                    move_uploaded_file($url_temp, $src);
                                }
                                $msg = "modificado";
                            } else if ($data == "existe") {
                                $msg = "El código ya existe";
                            } else {
                                $msg = "Error al modificar el Producto";
                            }
                        } else {
                            $msg = "No modifique el ID Producto";
                        }
                    }
                }
            } else {
                $msg = "No modifique el ID Medida o Categoría";
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarProducto($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Producto no es entero";
        } else {
            $validarProducto = $this->model->getProductoId($id);
            if ($validarProducto) {
                $data = $this->model->accionProducto(0, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al eliminar el Producto";
                }
            } else {
                $msg = "El ID del Producto no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function reingresar($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Producto no es entero";
        } else {
            $validarProducto = $this->model->getProductoId($id);
            if ($validarProducto) {
                $data = $this->model->accionProducto(1, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al reingresar el Producto";
                }
            } else {
                $msg = "El ID del Producto no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
