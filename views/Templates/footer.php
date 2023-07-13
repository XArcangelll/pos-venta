
      
                    </div>
                </main>


<footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>


        <div id="cambiarPass" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Modificar Contraseña</h5>
                        <button class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmCambiarPass" method="post" onsubmit="frmCambiarPass(event);">
                                <div class="form-group">
                                    <label for="clave_actual" class="mt-2">Contraseña Actual</label>
                                    <input id="clave_actual" class="form-control mt-2" type="password" name="clave_actual" placeholder="Contraseña Actual">
                                </div>
                                <div class="form-group">
                                    <label for="clave_nueva" class="mt-2">Contraseña Nueva</label>
                                    <input id="clave_nueva" class="form-control mt-2" type="password" name="clave_nueva" placeholder="Contraseña Nueva">
                                </div>
                                <div class="form-group">
                                    <label for="confirmar_clave" class="mt-2">Confirmar Contraseña</label>
                                    <input id="confirmar_clave" class="form-control mt-2" type="password" name="confirmar_clave" placeholder="Confirmar Contraseña">
                                </div>
                                <button type="submit" class="btn btn-primary mt-2  text-center">Modificar Contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo constant("URL")?>Assets/js/jquery.js" crossorigin="anonymous"></script>
        <script src="<?php echo constant("URL")?>Assets/js/jquery-ui.js"></script>
        <script src="<?php echo constant("URL")?>Assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo constant("URL")?>Assets/js/scripts.js"></script>
        <!--<script src="<?php echo constant("URL")?>Assets/js/Chart.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo constant("URL")?>Assets/demo/chart-area-demo.js"></script>
        <script src="<?php echo constant("URL")?>Assets/demo/chart-bar-demo.js"></script> -->
       
        <script src="<?php echo constant("URL")?>Assets/js/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo constant("URL")?>Assets/js/dataTables.js" crossorigin="anonymous"></script>
        <!-- borra la comentada de abajo si quieres q salgan opciones de pdf y excel en el datatable producots -->
        <!--<script src="<?php echo constant("URL")?>Assets/DataTables/datatables.min.js" crossorigin="anonymous"></script>-->
        <script src="<?php echo constant("URL")?>Assets/js/datatables-simple-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const base_url = "<?php echo constant("URL")?>";
        </script>
        
        <script src="<?php echo constant("URL")?>Assets/js/sweetalert2.js"></script>
        <script src="<?php echo constant("URL")?>Assets/js/funciones.js"></script>
      
      
      
    </body>
</html>