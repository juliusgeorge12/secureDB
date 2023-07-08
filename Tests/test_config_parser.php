<?php

use secureDB\components\config_parser\parser;

  require_once  dirname(__DIR__) . "\\vendor\\autoload.php";
 try {
  $parser = new parser('C:/project/secureDB/config/');
  var_dump($parser->get("edbuh"));
 } catch(Exception $e){
        echo $e->getMessage();

 }