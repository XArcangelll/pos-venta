<?php
include "Views/Templates/header.php";
?>

<div class="row">

    <div class="col-xl-4 col-md-6 mt-3">
            <div class="card bg-primary">
                <div class="card-body d-flex text-white">
                   Usuarios 
                   <i class="fas fa-user fa-2x ms-auto" ></i>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo constant("URL")?>Usuarios" class="text-white">Ver Detalle</a>
                    <span class="text-white"><?php echo $data["usuarios"]["total"] ?> </span>
                </div>
            </div>
    </div>
    <div class="col-xl-4 col-md-6 mt-3">
            <div class="card bg-success">
                <div class="card-body d-flex text-white">
                   Clientes
                   <i class="fas fa-users fa-2x ms-auto" ></i>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo constant("URL")?>Clientes" class="text-white">Ver Detalle</a>
                    <span class="text-white"><?php echo $data["clientes"]["total"] ?> </span>
                </div>
            </div>
    </div>

    <div class="col-xl-4 col-md-6 mt-3">
            <div class="card bg-secondary">
                <div class="card-body d-flex text-white">
                    Proveedores
                   <i class="fas fa-users fa-2x ms-auto" ></i>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo constant("URL")?>Clientes" class="text-white">Ver Detalle</a>
                    <span class="text-white"><?php echo $data["clientes"]["total"] ?> </span>
                </div>
            </div>
    </div>

    <div class="col-xl-4 col-md-6 mt-3">
            <div class="card bg-danger">
                <div class="card-body d-flex text-white">
                  Productos 
                   <i class="fas fa-shop fa-2x ms-auto" ></i>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo constant("URL")?>Productos" class="text-white">Ver Detalle</a>
                    <span class="text-white"><?php echo $data["productos"]["total"] ?></span>
                </div>
            </div>
    </div>

    <div class="col-xl-4 col-md-6 mt-3">
            <div class="card bg-warning">
                <div class="card-body d-flex text-white">
                    Ventas del día 
                   <i class="fas fa-cash-register fa-2x ms-auto" ></i>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo constant("URL")?>Compras/historialVentas" class="text-white">Ver Detalle</a>
                    <span class="text-white"><?php echo $data["ventas"]["total"] ?></span>
                </div>
            </div>
    </div>

    <div class="col-xl-4 col-md-6 mt-3">
            <div class="card bg-info">
                <div class="card-body d-flex text-white">
                    Ganancias del día
                   <i class="fas fa-money-bill fa-2x ms-auto" ></i>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo constant("URL")?>Compras/historialVentas" class="text-white">Ver Detalle</a>
                    <span class="text-white">S/. <?php echo $data["ganancias"]["total"] ?></span>
                </div>
            </div>
    </div>


</div>

<div class="row mt-3 ">
    <div class="col-xl-6">
        <div class="card" >
            <div class="card-header bg-dark text-white">
                Productos con stock mínimo (Unidad o Kilos)
            </div>
            <div class="card-body">
         
                <canvas id="stockMinimo"></canvas>
          
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                Productos más vendidos (Unidades o Kilos)
            </div>
            <div class="card-body">
               
            <canvas id="ProductosVendidos"></canvas>
          
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                Productos con stock mínimo (Gramos)
            </div>
            <div class="card-body">
         
                <canvas id="stockMinimo2"></canvas>
          
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                Productos más vendidos (gramos)
            </div>
            <div class="card-body">
               
            <canvas id="ProductosVendidos2"></canvas>
          
            </div>
        </div>
    </div>

</div>



<?php include "Views/Templates/footer.php";

?>