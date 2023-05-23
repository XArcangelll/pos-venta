<?php

include "Views/Templates/header.php";


?>



<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Clientes</li>
</ol>


<button class="btn btn-primary mb-2 " data-bs-toggle="modal" data-bs-target="#nuevo_cliente" onclick="btnNuevoCliente();" type="button">Nuevo <i class="fas fa-plus"></i></button>
<table class="table table-borderless text-center pt-2" id="tblClientes" width="100%">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">DNI</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Teléfono</th>
            <th class="text-center">Dirección</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<div id="nuevo_cliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="my-modal-title"></h5>
                <button class="close" id="cerrarForm" data-bs-dismiss="modal" onclick="cerrarFormCliente(event);" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <form method="post" id="frmCliente">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="dni" class="col-form-label">DNI</label>
                        <input id="dni" class="form-control" type="number" name="dni" placeholder="Documento de Identidad Nacional">
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="col-form-label">Nombre</label>
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre del Cliente">
                    </div>
                    <div class="form-group">
                        <label for="telefono" class="col-form-label">Teléfono</label>
                        <input id="telefono" class="form-control" type="number" name="telefono" placeholder="Teléfono">
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="col-form-label">Dirección</label>
                        <textarea id="direccion"  style="overflow:auto;resize:none"  class="form-control" type="text" name="direccion" placeholder="Dirección"></textarea>
                    </div>
                    <button class="btn btn-primary m-2" type="button" id="btnAccion" onclick="registrarCliente(event)">Registrar</button>
                    <button class="btn btn-danger m-2" type="button" data-bs-dismiss="modal" onclick="cerrarFormCliente(event);">Cerrar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include "Views/Templates/footer.php";

?>