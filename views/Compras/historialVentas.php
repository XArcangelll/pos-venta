<?php include "Views/Templates/header.php"; ?>


<div class="card p-1">

    <div class="card-header text-white bg-dark">
            <h4> Lista de ventas</h4>
    </div>

   
    <div class="row">
            <div class="col-md-2">
                    <div class="form-group">
                        <a href="<?php echo constant("URL")?>Compras/ventas" class="btn btn-primary  my-3"  type="button"  >Nueva Venta </a>
                    </div>
                </div> 
    </div>
    <div class="table-responsive">
    
<table class="table table-borderless text-center mt-2 pt-2" id="tblHistorialVenta">
    <thead class="table-dark">
        <tr>
            <th class="text-center">ID</th>
            <th  class="text-center"> Fecha</th>
            <th class="text-center">Usuario</th>
            <th class="text-center">Cliente</th>
            <th class="text-center">Total</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
    </div>

  

</div>

<?php include "Views/Templates/footer.php";?>