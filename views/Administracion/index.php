<?php include "Views/Templates/header.php";?>

<div class="card">
    <div class="card-header bg-dark text-white">
      Datos de la Empresa
    </div>
    <div class="card-body">
        <form id="frmEmpresa">
            <div class="row">
               <div class="col-md-4 mt-2">   <div class="form-group">
                    <input id="id" class="form-control" type="hidden" value="<?php echo $data["id"]?>" name="id">
                    <label for="ruc">RUC</label>
                    <input id="ruc" class="form-control" type="text" name="ruc" value="<?php echo $data["ruc"]?>" placeholder="RUC">
                </div></div>
               <div class="col-md-4 mt-2"> <div class="form-group">
                <label for="nombre">Nombre</label> 
                    <input id="nombre" class="form-control" type="text" name="nombre" value="<?php echo $data["nombre"]?>" placeholder="Nombre de la Empresa">
              </div>
               </div>
               <div class="col-md-4 mt-2">   <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input id="telefono" class="form-control" type="text" name="telefono" value="<?php echo $data["telefono"]?>" placeholder="Teléfono">
                </div></div>
               <div class="col-md-6 mt-2">  <div class="form-group">
                <label for="direccion">Dirección</label>
                    <input id="direccion" class="form-control" type="text" name="direccion" value="<?php echo $data["direccion"]?>" placeholder="Dirección">
                </div>
               </div>
               <div class="col-md-6 mt-2">
               <div class="form-group">
                <label for="mensaje" >Mensaje</label>
                    <input id="mensaje" class="form-control" type="text" name="mensaje" value="<?php echo $data["mensaje"]?>" placeholder="Mensaje">
                </div>
               </div>   

            </div>
             
                                   
                <button type="button" class="btn btn-primary mt-3" onclick="modificarEmpresa();">Modificar</button>
        </form>
    </div>
</div>


<?php include "Views/Templates/footer.php";?>