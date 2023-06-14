<?php

include "Views/Templates/header.php";


?>



<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Productos</li>
</ol>


<button class="btn btn-primary mb-2 " data-bs-toggle="modal" data-bs-target="#nuevo_producto" onclick="btnNuevoProducto();" type="button">Nuevo <i class="fas fa-plus"></i></button>
<div class="table-responsive">
<table class="table table-borderless text-center pt-2" id="tblProductos" width="100%">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Código</th>
            <th class="text-center">Descripción</th>
            <th class="text-center">Foto</th>
            <th class="text-center">P. Venta</th>
            <th class="text-center">Cantidad</th>
            <th class="text-center">Medida</th>
            <th class="text-center">Categoría</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody class="text-center">

    </tbody>
</table>
</div>

<div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="my-modal-title"></h5>
                <button class="close" id="cerrarForm" data-bs-dismiss="modal" onclick="cerrarFormProducto(event);" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frmProducto" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    
                    <div class="row text-center" id="codigo_descripcion">
                        <div class="col-md-6 m-auto">
                            <div class="form-group">
                                <label for="codigo" class="col-form-label">Código</label>
                               
                                <input id="codigo" class="form-control" type="number" name="codigo" placeholder="Código del Producto">
                            </div>
                        </div>
                        <div class="col-md-6 m-auto">
                            <div class="form-group">
                                <label for="descripcion" class="col-form-label">Descripción</label>
                                <input id="descripcion" class="form-control" type="text" name="descripcion" placeholder="Descripción del Producto">
                            </div>
                        </div>
                    </div>
                    <div class="row text-center" id="precio_cantidad">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="precio_compra" class="col-form-label">Precio de Compra</label>
                                <input id="precio_compra" class="form-control" type="number" name="precio_compra" placeholder="Precio de compra">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="precio_venta" class="col-form-label">Precio de Venta</label>
                                <input id="precio_venta" class="form-control" type="number" name="precio_venta" placeholder="Precio de venta">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cantidad" class="col-form-label">Cantidad</label>
                                <input id="cantidad" class="form-control" type="number" name="cantidad" placeholder="Cantidad ">
                            </div>
                        </div>
                    </div>

                    <div class="row text-center" id="medida_categoria">
                        <div class="col-md-6 m-auto">
                            <div class="form-group">
                                <label for="medida" class="col-form-label">Medida</label>
                                <select id="medida" class="form-control" name="medida">
                                    <?php foreach ($data["medidas"] as $row) { ?>
                                        <option value="<?php echo $row["id"] ?>"><?php echo $row["nombre"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 m-auto">
                            <div class="form-group">
                                <label for="categoria" class="col-form-label">Categoría</label>
                                <select id="categoria" class="form-control" name="categoria">
                                    <?php foreach ($data["categorias"] as $row) { ?>
                                        <option value="<?php echo $row["id"] ?>"><?php echo $row["nombre"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12 mt-2 mx-auto text-center">
                        <div class="form-group ">
                            <label>Foto</label>
                            <div class="card border-primary">
                                <div class="card-body">
                                    <div class="prevPhoto">
                                        <span class="btn btn-danger delPhoto notBlock"><i class="fa fa-xmark" ></i></span>
                                        <label for="foto" id="previo" class="btn"></label>
                                    </div>
                                    <div class="upimg">
                                        <input id="foto" class="form-control-file" type="file" name="foto" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-center pt-4">
                        <div class="form-group">
                            <button class="btn btn-primary m-2" type="button" id="btnAccion" onclick="registrarProducto(event)">Registrar</button>
                            <button class="btn btn-danger m-2" type="button" data-bs-dismiss="modal" onclick="cerrarFormProducto(event);">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php include "Views/Templates/footer.php";

?>