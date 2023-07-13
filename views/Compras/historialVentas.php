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

    <div class="row">
                <div class="col-md-12">
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                                <span class="input-group-text bg-info text-white"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" id="start_date" placeholder="Inicio de Fecha">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                                <span class="input-group-text bg-info text-white"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control" id="end_date" placeholder="Fin de Fecha">
                                        </div>
                                    </div>
                                    <div class="col-md-1 ">
                                    <div class="input-group mb-3">
                                        <button id="filter" class="btn btn-info m-auto" onclick="filtrarFecha(event)">Filtrar</button>  
                                    </div>
                                    </div>
                                    <div class="col-md-3 ">
                                    <div class="input-group mb-3">
                                        <button id="filter" class="btn btn-warning ms-auto w-75" onclick=" mostrarTodo(event);">Mostrar Todo</button>
                                        
                                    </div>
                                        
                                    </div>
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