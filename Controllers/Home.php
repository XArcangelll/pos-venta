<?php

class Home extends Controller{

    public function Index(){
      
      $this->views->getView($this,"index");
    }
}

?>