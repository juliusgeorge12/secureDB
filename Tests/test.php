<?php

use secureDB\app;
use secureDB\container\container;
use secureDB\contracts\DB\connection;
use secureDB\contracts\Logger\Logger;
use secureDB\contracts\TemplateEngine\Template_engine;

require_once  dirname(__DIR__) . "\\vendor\\autoload.php";

  try {
      $container = container::get_instance();
        (new app($container));
        var_dump($container->resolve(connection::class ,
         ['app' => $container , 'config'=> ['host' => 'localhost', 'db_username'=>'root' , 'db_password' => '', 'db_name' => 'test', 'port' => 3360]]
         )->connect()->get_connection());
  }
   catch(Exception $e){
        echo $e->getMessage();
  }
   


  
 