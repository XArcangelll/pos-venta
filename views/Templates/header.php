<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Panel de Administración</title>
        
        <link href="<?php echo constant("URL")?>Assets/css/style.min.css" rel="stylesheet" />
        <link href="<?php echo constant("URL")?>Assets/css/styles.css" rel="stylesheet" />
        <link href="<?php echo constant("URL")?>Assets/css/dataTables.css" rel="stylesheet" /> 
       <!--  <link href="<?php echo constant("URL")?>Assets/DataTables/datatables.min.css" rel="stylesheet" />-->
        <link href="<?php echo constant("URL")?>Assets/css/productos.css" rel="stylesheet" />
        
     <script src="<?php echo constant("URL")?>Assets/js/all.js" crossorigin="anonymous"></script>
     <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="<?php echo constant("URL")?>Assets/css/styles.css" rel="stylesheet" />
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="<?php echo constant("URL")?>Administracion/Home">Pos Venta</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#cambiarPass">Perfil</a></li>
                        <li><a class="dropdown-item" href="<?php echo constant("URL")?>Usuarios/salir">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-cogs text-primary"></i></div>
                               Administración
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-primary"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo constant("URL") ?>Usuarios"><i class="fas fa-user me-2 text-primary"></i>Usuarios</a>
                                    <a class="nav-link" href="<?php echo constant("URL") ?>Cajas"><i class="fas fa-box me-2 text-primary"></i>Cajas</a>
                                    <a class="nav-link" href="<?php echo constant("URL") ?>Administracion"><i class="fas fa-tools me-2 text-primary"></i>Configuración</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="<?php echo constant("URL")?>Clientes">
                                <div class="sb-nav-link-icon"><i class="fas fa-users text-primary"></i></div>
                               Clientes
                            </a>
                            <a class="nav-link" href="<?php echo constant("URL")?>Categorias">
                                <div class="sb-nav-link-icon"><i class="fa fa-clipboard text-primary"></i></div>
                               Categorías
                            </a>
                            <a class="nav-link" href="<?php echo constant("URL")?>Medidas">
                                <div class="sb-nav-link-icon"><i class="fa fa-ruler text-primary"></i></div>
                               Medidas
                            </a>
                            <a class="nav-link" href="<?php echo constant("URL")?>Productos">
                                <div class="sb-nav-link-icon"><i class="fa fa-bag-shopping text-primary"></i></div>
                               Productos
                            </a>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCompras" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart text-primary"></i></div>
                               Entradas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-primary"></i></div>
                            </a>
                            <div class="collapse" id="collapseCompras" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?php echo constant("URL")?>Compras">
                                <div class="sb-nav-link-icon"><i class="fa fa-money-bill text-primary"></i></div>
                              Compras
                            </a>
                                    <a class="nav-link" href="<?php echo constant("URL") ?>Compras/historial"><i class="fas fa-list me-2 text-primary"></i>Historial Compras</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVentas" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart text-primary"></i></div>
                               Salidas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down text-primary"></i></div>
                            </a>
                            <div class="collapse" id="collapseVentas" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?php echo constant("URL")?>Compras/ventas">
                                <div class="sb-nav-link-icon"><i class="fa fa-money-bill text-primary"></i></div>
                              Ventas
                            </a>
                                    <a class="nav-link" href="<?php echo constant("URL") ?>Compras/historialVentas"><i class="fas fa-list me-2 text-primary"></i>Historial Ventas</a>
                                </nav>
                            </div>

                            
                            
                        </div>
                    </div>
                    
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4 mt-2">
                    
                 
               

