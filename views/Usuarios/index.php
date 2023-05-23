<?php

include "Views/Templates/header.php"; 


?>



<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Usuarios</li>
</ol>


<button class="btn btn-primary mb-2 " data-bs-toggle="modal" data-bs-target="#nuevo_usuario" onclick="btnNuevoUser();" type="button">Nuevo <i class="fas fa-plus"></i></button>
<table class="table table-borderless text-center pt-2" id="tblUsuarios" width="100%">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Caja</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="my-modal-title"></h5>
                <button class="close" id="cerrarForm" data-bs-dismiss="modal" onclick="cerrarFormUser(event);" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frmUsuario">
                    <div class="form-group">
                        <label for="usuario" class="col-form-label">Usuario</label>
                        <input type="hidden" id="id" name="id">
                        <input id="usuario" class="form-control" type="text" name="usuario" placeholder="Usuario">
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="col-form-label">Nombre</label>
                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre del Usuario">
                    </div>

                    <div class="row" id="claves">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clave" class="col-form-label">Contraseña</label>
                                <input id="clave" class="form-control" type="password" name="clave" placeholder="Contraseña">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirmar" class="col-form-label">Confirmar Contraseña</label>
                                <input id="confirmar" class="form-control" type="password" name="confirmar" placeholder="Confirmar Contraseña">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="caja" class="col-form-label">Caja</label>
                        <select id="caja" class="form-control" name="caja">
                            <?php foreach($data["cajas"] as $row){ ?>
                            <option value="<?php echo $row["id"]?>"><?php echo $row["caja"]?></option>
                            <?php }?>
                        </select>
                    </div>

                    <button class="btn btn-primary m-2" type="button" id="btnAccion" onclick="registrarUser(event)">Registrar</button>
                    <button class="btn btn-danger m-2" type="button" data-bs-dismiss="modal" onclick="cerrarFormUser(event);">Cerrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php";

?>