<?php

use secureDB\app;
use secureDB\container\container;
use secureDB\contracts\Logger\Logger;
use secureDB\contracts\TemplateEngine\Template_engine;

require_once  dirname(__DIR__) . "\\vendor\\autoload.php";

  try {
      $container = container::get_instance();
        (new app($container));
        $logger = $container->resolve(Logger::class , ['path' => $container->get(Template_engine::class)->get('log_path')]);
        $logger->log_error('there is an error');
        $logger->log_action("i started the app");
  }
   catch(Exception $e){
        echo $e->getMessage();
  }
   


  
 