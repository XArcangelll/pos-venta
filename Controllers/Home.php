<?php

class Home extends Controller{

    public function __construct()
    {
      session_start();
      if(!empty($_SESSION["activo"])){

        if($_SESSION["rol"] == 1){
          header("location: ".constant("URL")."Administracion/Home");
        }else{
          header("location: ".constant("URL")."Clientes");
        }
        
    }
      parent::__construct();
    }

    public function Index(){
      
      $this->views->getView($this,"index");
    }
}

?>