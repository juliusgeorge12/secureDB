<?php

use secureDB\components\config_parser\parser;

  require_once  dirname(__DIR__) . "\\vendor\\autoload.php";
 try {
  $parser = new parser('C:/project/secureDB/config/');
  var_dump($parser->get("connections"));
  var_dump($parser->get("prepared_statement_enabled"));
 } catch(Exception $e){
        echo $e->getMessage();

 }