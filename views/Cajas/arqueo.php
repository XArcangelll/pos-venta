<?php

include "Views/Templates/header.php";


?>



<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Arqueo de Caja</li>
</ol>

<div id="botonesAbrirCerrarCaja">

<?php if(empty($data["data"])){ ?>
    <button class="btn btn-primary mb-2 " data-bs-toggle="modal" data-bs-target="#abrir_caja" onclick="arqueoCaja(event);" type="button">Abrir Caja</button>
<?php }else{ if(!empty($data["estado"])){ ?>
    
<button class="btn btn-danger mb-2 "  onclick="cerrarCaja(event);" type="button">Cerrar Caja</button>
    <?php } }?>
    </div>
<table class="table table-borderless text-center pt-2" id="tblArqueo" width="100%">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Monto Inicial</th>
            <th class="text-center">Monto Final</th>
            <th class="text-center">Fecha Apertura</th>
            <th class="text-center">Fecha Cierre</th>
            <th class="text-center">Total Ventas</th>
            <th class="text-center">Monto Total</th>
            <th class="text-center">Estado</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<div id="abrir_caja" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="my-modal-title">Arqueo Caja</h5>
                <button class="close" id="cerrarForm" data-bs-dismiss="modal" onclick="cerrarFormArqueo(event);" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <form method="post" id="frmAbrirCaja" onsubmit="abrirArqueo(event);">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="monto_inicial" class="col-form-label">Monto Inicial</label>
                        <input id="monto_inicial" class="form-control" type="text" name="monto_inicial" placeholder="Monto Inicial" >
                    </div>
                    <div id="ocultarInfo">
                    <div class="form-group">
                        <label for="monto_final" class="col-form-label">Monto Final</label>
                        <input id="monto_final" class="form-control" type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label for="total_ventas" class="col-form-label">Total Ventas</label>
                        <input id="total_ventas" class="form-control" type="text" disabled>
                    </div>
                    <div class="form-group">
                        <label for="monto_total" class="col-form-label">Monto Total</label>
                        <input id="monto_total" class="form-control" type="text" disabled>
                    </div>
                    </div>
                   
                        <button class="btn btn-primary mt-3" id="btnAccion" type="submit"></button>
                
                    <button class="btn btn-danger mt-3" type="button" data-bs-dismiss="modal" onclick="cerrarFormArqueo(event);">Cancelar</button>
                  
                </form>
            </div>
        </div>
    </div>
</div>


<?php include "Views/Templates/footer.php";

?>