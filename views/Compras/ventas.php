<?php

include "Views/Templates/header.php";


?>


<div class="card">

    <div class="card-header text-white bg-dark">
            <h4> Nueva Venta</h4>
    </div>

    <div class="card-body">
            <div class="row">
            <div class="col-md-2">
                    <div class="form-group">
                        <input class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#buscar_cliente" type="button" onclick="listarBuscarCliente();" value="Buscar Cliente">
                    </div>
                </div> 
                <div class="col-md-10">
                    <div class="form-group">
                  <div class="d-flex justify-content-end">
                        <button class="btn btn-danger mb-3 invisible" id="removerCliente" type="button" onclick="removerCliente(event);" disabled>Remover Cliente</button>
                    </div>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="hidden" id="idcliente" name="idcliente">
                        <label for="dni">DNI</label>
                        <input id="dni" class="form-control" type="text" name="dni"  placeholder="DNI" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombrecliente">Nombre</label>
                        <input id="nombrecliente" class="form-control" type="text" name="nombrecliente" placeholder="Nombre" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input id="telefono" class="form-control" type="number" name="telefono" placeholder="Teléfono" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input id="direccion" class="form-control" type="text" name="direccion" placeholder="Dirección" disabled>
                    </div>
                </div>
            </div>
    </div>

    <div class="card-body">
        <form  id="frmCompra">
            <div class="row">
            <div class="col-md-2">
                    <div class="form-group">
                        <input class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#buscar_producto" type="button" onclick="listarBuscarProductoVenta();" value="Buscar Producto">
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="hidden" id="id" name="id">
                        <label for="codigo">Código de Barras</label>
                        <input id="codigo" class="form-control" type="text" name="codigo" onkeyup="buscarCodigoVenta(event);" placeholder="Código de Barras">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre">Descripción</label>
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Descripción" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input id="stock" class="form-control" type="number" name="stock" placeholder="Stock" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input id="cantidad" class="form-control" type="text" name="cantidad" onkeyup="calcularPrecioVenta(event);" placeholder="Cantidad" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input id="precio" class="form-control" type="number" name="precio" placeholder="Precio Venta S/." disabled>
                    </div>
                </div>
                <div class="col-md-2 mt-2">
                    <div class="form-group">
                        <label for="precio_total">Sub Total</label>
                        <input id="precio_total" class="form-control" type="number" name="precio_total" placeholder="Sub Total S/." disabled>
                    </div>
                </div>

                <div class="col-md-3 mt-4 invisible" id="colAdicional">
                <div class="w-100 p-3">
                <label class="ps-3 me-1 h4" for="checkbox-3" >Adicional</label>
                <input class="form-check-input  h4 pb-2" type="checkbox" name="adicional" disabled  id="adicional"/>
                </div>
                </div>

                <div class="d-flex flex-row-reverse col-md-7 mt-2">
                    <div class="form-group">
                        <button type="button" id="agregar" onclick="agregarProductoDetalleTempVenta(event);" class="btn btn-danger mt-2 invisible" type="number" disabled>Agregar</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="table-responsive">

<table class="table table-hover table-borderless text-center mt-2 pt-2">
    <thead  class="table-dark">
        <tr>
        <th>ID</th>
        <th>Descripción</th>
        <th>Cantidad</th>
        <th>Aplicar</th>
        <th>Descuento</th>
        <th>Precio</th>
        <th>Sub Total</th>
        <th width="75px"></th>
        </tr>
        
    </thead>
    <tbody id="tblDetalleTempVenta">
    
</tbody>
</table>

</div>
                <div class="d-flex justify-content-end">
                    <div class="form-group">
                        <label for="total" class="fw-bold">Total</label>
                        <input id="total" class="form-control" type="text" name="total" placeholder="Precio Total" disabled>

                        
                        <button type="button"  id="generarVenta"  class=" btn btn-primary mt-2 invisible" onclick="generarVenta(event);" disabled>Generar Venta</button>
                        <button type="button" id="anularVenta" class=" btn btn-danger mt-2 invisible" onclick="anularVenta(event);" disabled>Anular Venta</button>  
                       
        
                    </div>
                </div>
                
            




<div id="buscar_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="my-modal-title">Buscar Producto</h5>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
            <div class="table-responsive">
            <table class="table table-borderless text-center pt-2" id="tblProductosModal" width="100%">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Código</th>
            <th class="text-center">Descripción</th>
            <th class="text-center">Stock</th>
            <th class="text-center">Precio</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
            </div>

            </div>
        </div>
    </div>
</div>


<div id="buscar_cliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="my-modal-title">Buscar Cliente</h5>
                <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
            <div class="table-responsive">
            <table class="table table-borderless text-center pt-2" id="tblClientesModal" width="100%">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">DNI</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Teléfono</th>
            <th class="text-center">Dirección</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
            </div>

            </div>
        </div>
    </div>
</div>




<?php include "Views/Templates/footer.php";

?>