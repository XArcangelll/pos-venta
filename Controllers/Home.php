<?php

class Home extends Controller{

    public function __construct()
    {
      session_start();
      if(!empty($_SESSION["activo"])){
        header("location: ".constant("URL")."Usuarios");
    }
      parent::__construct();
    }

    public function Index(){
      
      $this->views->getView($this,"index");
    }
}

?>