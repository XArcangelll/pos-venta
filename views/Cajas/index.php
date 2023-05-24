<?php

include "Views/Templates/header.php";


?>



<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Cajas</li>
</ol>


<button class="btn btn-primary mb-2 " data-bs-toggle="modal" data-bs-target="#nuevo_caja" onclick="btnNuevoCaja();" type="button">Nuevo <i class="fas fa-plus"></i></button>
<table class="table table-borderless text-center pt-2" id="tblCajas" width="100%">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Caja</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<div id="nuevo_caja" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="my-modal-title"></h5>
                <button class="close" id="cerrarForm" data-bs-dismiss="modal" onclick="cerrarFormCaja(event);" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <form method="post" id="frmCaja">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="caja" class="col-form-label">Caja</label>
                        <input id="caja" class="form-control" type="text" name="caja" placeholder="Caja">
                    </div>
                    <button class="btn btn-primary m-2" type="button" id="btnAccion" onclick="registrarCaja(event)">Registrar</button>
                    <button class="btn btn-danger m-2" type="button" data-bs-dismiss="modal" onclick="cerrarFormCaja(event);">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php include "Views/Templates/footer.php";

?>