<?php

date_default_timezone_set("America/Lima");
error_reporting(E_ALL); // Error/Exception engine, always use E_ALL

ini_set('ignore_repeated_errors', TRUE); // always use TRUE

ini_set('display_errors', FALSE); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', TRUE); // Error/Exception file logging engine.

ini_set("error_log", "php-error.log");
error_log( "Inicio de la Aplicación" );

    require_once "Config/Config.php";

    $ruta = !empty($_GET["url"]) ? $_GET["url"] : "Home/index";

    $array = explode("/",$ruta);

    $controller = $array[0];
    $metodo = "index";
    $parametro = "";

    if(!empty($array[1])){
        if(!empty($array[1]) != ""){
            $metodo = $array[1];
        }
    }

    if(!empty($array[2])){
        if(!empty($array[2]) != ""){
                for($i=2; $i < count($array);$i++){
                    $parametro .= $array[$i].",";
                }
                $parametro =  trim($parametro,",");
        }
    }

    require_once "Config/App/Autoload.php";

   $dirControllers = "Controllers/" .$controller.".php";

   if(file_exists($dirControllers)){
    require_once $dirControllers;
    $controller = new $controller();

        if(method_exists($controller,$metodo)){
            $controller->$metodo($parametro);
        }else{
           header("location: ".constant("URL")."Errors");
        }

   }else{
    header("location: ".constant("URL")."Errors");
   }



?>