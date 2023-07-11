<?php

use secureDB\app;
use secureDB\container\container;

require_once  dirname(__DIR__) . "\\vendor\\autoload.php";

 try {
        (new app(container::get_instance()));
 }
  catch(Exception $e){
        echo $e->getMessage();
  }
 