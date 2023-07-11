<?php

use secureDB\app;
use secureDB\container\container;
/*
require_once  dirname(__DIR__) . "\\vendor\\autoload.php";

 try {
        (new app(container::get_instance()));
 }
  catch(Exception $e){
        echo $e->getMessage();
  }
  */

  $p = "C:/project/secureDB/error.log";
 // $file = fopen($p , 'a');
  $txt = "Hello, how are you doing, i am trying again \r\n";
  //fwrite($file , $txt , strlen($txt));
  file_put_contents($p, $txt);

  
 