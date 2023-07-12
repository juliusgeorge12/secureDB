<?php

use secureDB\app;
use secureDB\components\Logger\Logger;
use secureDB\components\Template_engine\template_engine;
use secureDB\container\container;

require_once  dirname(__DIR__) . "\\vendor\\autoload.php";

 try {
      $container = container::get_instance();
        (new app($container));
       // $logger = $container->resolve(Logger::class , ['path' => $container->get(template_engine::class)->get('log_path')]);
        
 }
  catch(Exception $e){
        echo $e->getMessage();
  }
   


  
 