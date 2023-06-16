<?php

class Compras extends Controller
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

    public function ventas()
    {
        
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }
        $this->views->getView($this, "ventas");
    }

    public function validacionStock(int $id){
        $id_usuario = $_SESSION["id_usuario"];
        $data = $this->model->getProductoId($id);
        $data2 = $this->model->getProductoDetalleVenta($id,$id_usuario);
        $cantidad = $data["cantidad"];
        if($data2){
        $cantidad_temp = $data2["cantidad"];
        }else{
            $cantidad_temp = null;
        }
        $msg = array("msg"=>"ok","cantidad_temp" => $cantidad_temp, "cantidad" => $cantidad,"medida"=>$data["id_medida"]);
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarCodigoConStock($codigo){
        $id_usuario = $_SESSION["id_usuario"];
        $data = $this->model->getProductoCod($codigo);
        $cantidad_temp = null;
        if($data){
            
        $data2 = $this->model->getProductoDetalleVenta($data["id"],$id_usuario);
        if($data2){
        $cantidad_temp = $data2["cantidad"];
        }else{
            $cantidad_temp = null;
        }
        }

        $msg = array("msg"=>"ok","cantidad_temp" => $cantidad_temp, "data" => $data);
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function buscarCodigo($cod)
    {

        $data = $this->model->getProductoCod($cod);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarIdCliente($id)
    {

        $data = $this->model->getClienteid($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

   
    public function listarProductos()
    {
        $data = $this->model->getProductos();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] =  '<div>
            <button type="button" onclick="agregarCodigoProducto(event,' . $data[$i]["codigo"] . ');" class="btn btn-success"  data-bs-dismiss="modal"><i class="fas fa-plus"></i> Agregar</button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarProductosVenta()
    {
        $data = $this->model->getProductos();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] =  '<div>
            <button type="button" onclick="agregarCodigoProductoVenta(event,' . $data[$i]["codigo"] . ');" class="btn btn-success"  data-bs-dismiss="modal"><i class="fas fa-plus"></i> Agregar</button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarClientes()
    {
        $data = $this->model->getClientes();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] =  '<div>
            <button type="button" onclick="agregarCodigoCliente(event,' . $data[$i]["id"] . ');" class="btn btn-success"  data-bs-dismiss="modal"><i class="fas fa-plus"></i> Agregar</button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function ingresar()
    {

       /* $data = file_get_contents( "php://input" ); //$data is now the string '[1,2,3]';

        $data = json_decode( $data );

        
       
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
        */

        $id = $_POST["id"];
        $datos = $this->model->getProductoId($id);
        $id_producto = $datos["id"];
        $id_usuario = $_SESSION["id_usuario"];
        $precio = $datos["precio_compra"];
        $cantidad = $_POST["cantidad"];

        $comprobar = $this->model->consultarDetalleTemporal($id_producto, $id_usuario);
        if (empty($comprobar)) {
            if($datos["id_medida"] != 2) {
                $sub_total = $precio * $cantidad;
            }else{
                $sub_total = ($precio * $cantidad)/1000;
            }
            $data = $this->model->registrarDetalleTemp($id_producto, $id_usuario, $precio, $cantidad, $sub_total);
            if ($data == "ok") {
                $msg = "ok";
            } else {
                $msg = "Error al ingresar el producto";
            }
        } else {
            $total_cantidad = $comprobar["cantidad"] + $cantidad;
            if($datos["id_medida"] != 2) {
                $sub_total = $precio * $total_cantidad;
            }else{
                $sub_total = ($precio * $total_cantidad)/1000;
            }
            $data = $this->model->actualizarDetalleTemp($precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
            if ($data == "modificado") {
                $msg = "ok";
            } else {
                $msg = "Error al actualizar el producto";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function ingresarVenta()
    {
       
        $id = $_POST["id"];
        $datos = $this->model->getProductoId($id);
        $id_producto = $datos["id"];
        $id_usuario = $_SESSION["id_usuario"];
        $precio = $datos["precio_venta"];
        $cantidad = $_POST["cantidad"];
        $adicional = 0;
        if(isset($_POST["adicional"])){
            
        if($_POST["adicional"]){
            $adicional = $datos["adicional"];
            $precio = $datos["precio_venta"] + $adicional;
         }else{
           $adicional = 0;
           $precio = $datos["precio_venta"] ;
         }
        }

        $comprobar = $this->model->consultarDetalleTemporalVenta($id_producto, $id_usuario,$precio);
        if (empty($comprobar)) {
            if($datos["id_medida"] != 2) {
                $sub_total = ($datos["precio_venta"] * $cantidad) + ($adicional * $cantidad );
            }else{
                $sub_total = ($datos["precio_venta"] * $cantidad)/1000;
            }
           
            $data = $this->model->registrarDetalleTempVenta($id_producto, $id_usuario, $precio, $cantidad, $sub_total);
            if ($data == "ok") {
                $msg = "ok";
            } else {
                $msg = "Error al ingresar el producto";
            }
        } else {
            $total_cantidad = $comprobar["cantidad"] + $cantidad;
            if($datos["id_medida"] != 2) {
            $sub_total = ($total_cantidad * $datos["precio_venta"])  + ($total_cantidad * $adicional );
            }else{
                $sub_total = ($total_cantidad * $datos["precio_venta"])/1000 ;
            }
            $data = $this->model->actualizarDetalleTempVenta($precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
            if ($data == "modificado") {
                $msg = "ok";
            } else {
                $msg = "Error al actualizar el producto";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listar()
    {

        $id_usuario = $_SESSION["id_usuario"];
        $data["detalle"] = $this->model->getDetalleTemporal($id_usuario);
        $data["total_pagar"] = $this->model->calcularCompra($id_usuario);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarVenta()
    {

        $id_usuario = $_SESSION["id_usuario"];
        $data["detalle"] = $this->model->getDetalleTemporalVenta($id_usuario);
       foreach( $data["detalle"] as $clave => $row){
        $data["detalle"][$clave]["descripcion_detalle"] = "";
        $consulta = $this->model->consultarPrecioProducto($row["id_producto"]);
        if($row["precio"] > $consulta["precio_venta"]){
           
            $descripcion_adicional = $row["descripcion"] . " (ADICIONAL)";
       $data["detalle"][$clave]["descripcion_detalle"] = $descripcion_adicional;
       }else{
       $data["detalle"][$clave]["descripcion_detalle"] = $row["descripcion"];
       }
    }
        $data["total_pagar"] = $this->model->calcularVenta($id_usuario);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteDetalle($id)
    {
        $id_usuario = $_SESSION["id_usuario"];
        $data = $this->model->deleteDetalle($id, $id_usuario);
        if ($data == "ok") {
            $msg = "ok";
        } else {
            $msg = "error";
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function deleteDetalleVenta($id)
    {
        $id_usuario = $_SESSION["id_usuario"];
        $data = $this->model->deleteDetalleVenta($id, $id_usuario);
        if ($data == "ok") {
            $msg = "ok";
        } else {
            $msg = "error";
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarCompra()
    {
        $id_usuario =  $_SESSION["id_usuario"];
        $total = $this->model->calcularCompra($id_usuario);
        $data = $this->model->registraCompra($id_usuario, $total["total"]);
        if ($data == "ok") {
            $detalle = $this->model->getDetalleTemporal($id_usuario);
            $id_compra =  $this->model->id_compra($id_usuario);
            foreach ($detalle as $row) {
                $id_producto = $row["id_producto"];
                $cantidad = $row["cantidad"];
                $precio = $row["precio"];
                $sub_total = $row["sub_total"];
                $this->model->registrarDetalleCompra($id_compra["id"], $id_producto, $cantidad, $precio, $sub_total);
                $stock_actual = $this->model->getProductosStock($id_producto);
                $stock = $stock_actual["cantidad"] + $cantidad;
                $this->model->actualizarStock($stock,$id_producto);
            }

            $vaciar = $this->model->vaciarDetalleTemp($id_usuario);
            if($vaciar == "ok"){
                $msg = array("msg"=>"ok","id_compra" => $id_compra["id"]);
            }

        } else {
            $msg = array("msg"=>"Error al realizar la compra");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    

    public function registrarVenta($id)
    {
        $id_cliente = "";
        if(empty($id)){
            $id_cliente = 1;
        }else if(!is_numeric($id)){
            $id_cliente = 1;
        }else{
            $validarIdCliente = $this->model->getClienteId($id);
            if($validarIdCliente){
                    $id_cliente = $id;
            }else{
                $id_cliente = 1;
            }
        }

        $id_usuario =  $_SESSION["id_usuario"];
        $total = $this->model->calcularVenta($id_usuario);
        $detalle = $this->model->getDetalleTemporalVenta($id_usuario);
        foreach($detalle as $row){
            $id_producto = $row["id_producto"];
            $cantidad = $row["cantidad"];
            $dataproducto = $this->model->getProductoId($id_producto);
            if($cantidad > $dataproducto["cantidad"] ){
                $msg = array("msg"=>"productoerror","cantidad_temp"=>$cantidad,"data"=>$dataproducto);
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                die();
            }
        }
        $data = $this->model->registraVenta($id_usuario,$id_cliente, $total["total"]);
        if ($data == "ok") {
        
            $id_venta =  $this->model->id_venta($id_usuario);
            foreach ($detalle as $row) {
                $id_producto = $row["id_producto"];
                $cantidad = $row["cantidad"];
                $precio = $row["precio"];
                $sub_total = $row["sub_total"];
                $this->model->registrarDetalleVenta($id_venta["id"], $id_producto, $cantidad, $precio, $sub_total);
                $stock_actual = $this->model->getProductosStock($id_producto);
                $stock = $stock_actual["cantidad"] - $cantidad;
                $this->model->actualizarStock($stock,$id_producto);
            }

            $vaciar = $this->model->vaciarDetalleTempVenta($id_usuario);
            if($vaciar == "ok"){
                $msg = array("msg"=>"ok","id_venta" => $id_venta["id"]);
            }

        } else {
            $msg = array("msg"=>"Error al realizar la Venta");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function anularVenta(){
        $id_usuario =  $_SESSION["id_usuario"];
        $vaciar = $this->model->vaciarDetalleTempVenta($id_usuario);
        if($vaciar == "ok"){
            $msg = array("msg"=>"ok");
        }else{
            $msg = array("msg"=>"error");
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function generarPdf($id_compra)
    {
        $empresa = $this->model->getEmpresa();
        $productos = $this->model->getProCompra($id_compra);
        require('Libraries/fpdf/fpdf.php');
        header('Content-Type: text/html; charset=UTF-8');
        $pdf = new FPDF('P','mm',array(120,200));
        $pdf->AddPage();
        $pdf->SetMargins(2,0,0);
        $pdf->setTitle('Reporte Compra');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(95, 10, mb_convert_encoding($empresa["nombre"], 'ISO-8859-1', 'UTF-8'),0,1,'C');
        $pdf->Image(constant("URL").'Assets/img/logo.png',95,20,20,20);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, 'Ruc: ',0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, $empresa["ruc"],0,1,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, mb_convert_encoding('Teléfono: ', 'ISO-8859-1', 'UTF-8') ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, $empresa["telefono"],0,1,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, mb_convert_encoding('Dirección: ', 'ISO-8859-1', 'UTF-8') ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, mb_convert_encoding($empresa["direccion"], 'ISO-8859-1', 'UTF-8'),0,1,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, 'Folio: ' ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, $id_compra,0,1,'L');
        $pdf->Ln();
        //encabezado de productos
        $pdf->SetFillColor(0,0,0);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(75,5, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'),0,0,'L',true);
        $pdf->Cell(12,5,"Cant",0,0,'L',true);
        $pdf->Cell(12,5,"Precio",0,0,'L',true);
        $pdf->Cell(15,5,"Sub Total",0,1,'L',true);
        $pdf->SetTextColor(0,0,0);
        $total = 0.0;
        foreach($productos as $row){
            $pdf->Cell(75,5, mb_convert_encoding($row["descripcion"], 'ISO-8859-1', 'UTF-8'),0,0,'L');
            $pdf->Cell(12,5,$row["cantidad"],0,0,'L');
             $pdf->Cell(12,5,$row["precio"],0,0,'L');
            $pdf->Cell(15,5,$row["sub_total"],0,1,'L');
            $total = $row["total"];
        }
        $pdf->Ln();
        $pdf->Cell(115,5,'Total a Pagar',0,1,'R');
        $pdf->Cell(115,5,'S/. '.$total,0,1,'R');

        $pdf->Output();
    }

    public function generarPdfVenta($id_venta)
    {
        $empresa = $this->model->getEmpresa();
        $cliente = $this->model->getClienteVenta($id_venta);
        $productos = $this->model->getProVenta($id_venta);
        require('Libraries/fpdf/fpdf.php');
        header('Content-Type: text/html; charset=UTF-8');
        $pdf = new FPDF('P','mm',array(120,200));
        $pdf->AddPage();
        $pdf->SetMargins(2,0,0);
        $pdf->setTitle('Reporte Venta');
        if($cliente["estado"] == 0){
             $pdf->SetTextColor(255,0,0);
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(95, 10, mb_convert_encoding("VENTA ANULADA", 'ISO-8859-1', 'UTF-8'),0,1,'C');
        }
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(95, 10, mb_convert_encoding($empresa["nombre"], 'ISO-8859-1', 'UTF-8'),0,1,'C');
        $pdf->Image(constant("URL").'Assets/img/logo.png',95,20,20,20);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, 'Ruc: ',0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, $empresa["ruc"],0,1,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, mb_convert_encoding('Teléfono: ', 'ISO-8859-1', 'UTF-8') ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, $empresa["telefono"],0,1,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, mb_convert_encoding('Dirección: ', 'ISO-8859-1', 'UTF-8') ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, mb_convert_encoding($empresa["direccion"], 'ISO-8859-1', 'UTF-8'),0,1,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, 'Folio: ' ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, $id_venta,0,1,'L');
        //cliente
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, 'Datos del Cliente:',0,1,'L');
       
        $pdf->Cell(20,5, mb_convert_encoding('DNI: ', 'ISO-8859-1', 'UTF-8') ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, mb_convert_encoding($cliente["dni"], 'ISO-8859-1', 'UTF-8'),0,1,'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(20,5, 'Nombres: ' ,0,0,'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,5, mb_convert_encoding($cliente["nombre"], 'ISO-8859-1', 'UTF-8'),0,1,'L');
        $pdf->Ln();

        //encabezado de productos
        $pdf->SetFillColor(0,0,0);
        $pdf->SetTextColor(255,255,255);
        $pdf->Cell(75,5, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'),0,0,'L',true);
        $pdf->Cell(12,5,"Cant",0,0,'L',true);
        $pdf->Cell(12,5,"Precio",0,0,'L',true);
        $pdf->Cell(15,5,"Sub Total",0,1,'L',true);
        $pdf->SetTextColor(0,0,0);
        $total = 0.0;
        foreach($productos as $row){
            $dato = $this->model->consultarPrecioProducto($row["id_producto"]);
            if($row["precio"] > $dato["precio_venta"]){
                $pdf->Cell(75,5, mb_convert_encoding($row["descripcion"]. " (ADICIONAL)", 'ISO-8859-1', 'UTF-8'),0,0,'L');
            }else{
                $pdf->Cell(75,5, mb_convert_encoding($row["descripcion"], 'ISO-8859-1', 'UTF-8'),0,0,'L');
            }
         
            $pdf->Cell(12,5,$row["cantidad"],0,0,'L');
             $pdf->Cell(12,5,$row["precio"],0,0,'L');
            $pdf->Cell(15,5,$row["sub_total"],0,1,'L');
            $total = $row["total"];
        }
        $pdf->Ln();
        $pdf->Cell(115,5,'Total a Pagar',0,1,'R');
        $pdf->Cell(115,5,'S/. '.$total,0,1,'R');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Output();
    }

    public function historial(){
        
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }
        $this->views->getView($this, "historial");
    }

    public function historialVentas(){
        
        if (empty($_SESSION["activo"])) {
            header("location: " . constant("URL"));
        }
        $this->views->getView($this, "historialVentas");
    }

    public function listarHistorial(){
        $data = $this->model->getHistorialCompras();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Pagado</span></h5>';
            }else{
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Anulado</span></h5>';
            }
            $data[$i]['acciones'] =  '
            <a  href="'.constant("URL")."Compras/generarPdf/".$data[$i]["id"].'" class="btn btn-danger" target="_blank"><i class="fa fa-file-pdf"></i></a>
            ';
            $data[$i]['total'] = 'S/. '.$data[$i]['total'] ;

        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        die();
    }

    
    public function listarHistorialVentas(){
        $data = $this->model->getHistorialVentas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = ' <h5><span class="badge bg-success">Pagado</span></h5>';
                $data[$i]['acciones'] =  '
                <div>
                <a  href="'.constant("URL")."Compras/generarPdfVenta/".$data[$i]["id"].'" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf"></i></a>
                <button type="button" onclick="pendienteVenta(' . $data[$i]["id"] . ');" class="btn btn-warning text-white" ><i class="fa fa-rotate"></i></button>
                <button type="button" onclick="AnularVentaId(' . $data[$i]["id"] . ');" class="btn btn-danger" ><i class="fas fa-ban"></i></button>
                </div>';
            }else if($data[$i]['estado'] == 2){
                $data[$i]['estado'] = ' <h5><span class="badge bg-warning">Pendiente</span></h5>';
                $data[$i]['acciones'] =  '
                <div>
                <a  href="'.constant("URL")."Compras/generarPdfVenta/".$data[$i]["id"].'" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf"></i></a>
                <button type="button" onclick="pagadoVenta(' . $data[$i]["id"] . ');" class="btn btn-success" ><i class="fa fa-rotate"></i></button>
                <button type="button" onclick="AnularVentaId(' . $data[$i]["id"] . ');" class="btn btn-danger " ><i class="fas fa-ban"></i></button>
               
                </div>';
            }else{
                $data[$i]['estado'] = '<h5><span class="badge bg-danger">Anulado</span></h5>';
                $data[$i]['acciones'] =  '
                <div>
                <a  href="'.constant("URL")."Compras/generarPdfVenta/".$data[$i]["id"].'" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf"></i></a>
                <button type="button"  class="btn btn-secondary" disabled><i class="fas fa-clipboard"></i></button>
                <button type="button"  class="btn btn-secondary" disabled><i class="fas fa-ban"></i></button>
                </div>';
            }
         
            $data[$i]['total'] = 'S/. '.$data[$i]['total'] ;

        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        die();
    }

    public function anularVentaEstado($id_venta){

        if (!is_numeric($id_venta)) {
            $msg = "El ID venta no es entero";
        } else {
            $validarVenta = $this->model->getVentaId($id_venta);
            if ($validarVenta) {
                $data = $this->model->AnularEstadoVenta(0, $id_venta);
                if ($data == 1) {
                    $detalle = $this->model->getDetalleVenta($id_venta);
                    foreach($detalle as $row){
                        $id_producto = $row["id_producto"];
                        $cantidad = $row["cantidad"];
                        $stock_actual = $this->model->getProductosStock($id_producto);
                        $stock = $stock_actual["cantidad"] + $cantidad;
                        $this->model->actualizarStock($stock,$id_producto);
                    }
                    $msg = "ok";
                } else {
                    $msg = "Error al anular la venta";
                }
            } else {
                $msg = "El ID de la venta no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();

    }

    public function pagadoVenta($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Venta no es entero";
        } else {
            $validarVenta = $this->model->getVentaId($id);
            if ($validarVenta) {
                $data = $this->model->accionEstadoVenta(1, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al actualizar Venta";
                }
            } else {
                $msg = "El ID de la venta no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pendienteVenta($id)
    {
        if (!is_numeric($id)) {
            $msg = "El ID Venta no es entero";
        } else {
            $validarVenta = $this->model->getVentaId($id);
            if ($validarVenta) {
                $data = $this->model->accionEstadoVenta(2, $id);
                if ($data == 1) {
                    $msg = "ok";
                } else {
                    $msg = "Error al actualizar Venta";
                }
            } else {
                $msg = "El ID de la venta no existe";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


}
