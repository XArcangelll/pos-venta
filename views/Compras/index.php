<?php

include "Views/Templates/header.php";


?>


<div class="card">

    <div class="card-header text-white bg-dark">
            <h4> Nueva Compra</h4>
    </div>

    <div class="card-body">
        <form  id="frmCompra">
            <div class="row">
            <div class="col-md-2">
                    <div class="form-group">
                        <input class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#buscar_producto" type="button" onclick="listarBuscarProducto();" value="Buscar Producto">
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="hidden" id="id" name="id">
                        <label for="codigo">Código de Barras</label>
                        <input id="codigo" class="form-control" type="text" name="codigo" onkeyup="buscarCodigo(event);" placeholder="Código de Barras">
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
                        <input type="hidden" name="medida" id="medida">
                        <label for="cantidad">Cantidad</label>
                        <input id="cantidad" class="form-control" type="text" name="cantidad" onkeyup="calcularPrecio(event);" placeholder="Cantidad" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input id="precio" class="form-control" type="number" name="precio" placeholder="Precio Compra S/." disabled>
                    </div>
                </div>
                <div class="col-md-2 mt-2">
                    <div class="form-group">
                        <label for="precio_total">Sub Total</label>
                        <input id="precio_total" class="form-control" type="number" name="precio_total" placeholder="Sub Total S/." disabled>
                    </div>
                </div>
                
                <div class="d-flex flex-row-reverse col-md-10 mt-2">
                    <div class="form-group">
                        <button type="button" id="agregar" onclick="agregarProductoDetalleTemp(event);" class="btn btn-danger mt-2 invisible" type="number" disabled>Agregar</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="table-responsive">

<table class="table table-borderless text-center mt-2 pt-2">
    <thead  class="table-dark">
        <tr>
        <th>ID</th>
        <th>Descripción</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Sub Total</th>
        <th width="75px"></th>
        </tr>
        
    </thead>
    <tbody id="tblDetalleTemp">
    
</tbody>
</table>

</div>




<div class="row">
                <div class="col-md-3 ms-auto">
                    <div class="form-group">
                        <label for="total" class="fw-bold">Total</label>
                        <input id="total" class="form-control" type="text" name="total" placeholder="Precio Total" disabled>
                        <div class="d-grid">
                        <button type="button" id="generarCompra" class="btn btn-primary mt-2 invisible" onclick="generarCompra(event);" disabled>Generar Compra</button>
                        </div>
                    </div>
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




<?php include "Views/Templates/footer.php";

?>