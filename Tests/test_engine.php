<?php

use secureDB\components\Template_engine\template_engine as Template_engineTemplate_engine;
use secureDB\container\container;
use secureDB\contracts\TemplateEngine\Template_engine;

 require_once  dirname(__DIR__) . "\\vendor\\autoload.php";

  $container = new container;

  $container->bind(Template_engine::class , Template_engineTemplate_engine::class);
 
  $template_engine = $container->get(Template_engine::class);
  $template_engine->bind('ROOT' , str_replace('\\' , '/' ,dirname(__DIR__)));
  $template_engine->add('config_path' , '{ROOT}/config/');
  $template_engine->add('message' , 'Hello {name} , how are you doing, the root path is {ROOT}');
  try {
  echo $template_engine->get('config_path' , ['ROOT' => "hey you there"]);
  echo $template_engine->get('message' , ["name" => "Emmanuel Ese"]);
  } catch (Exception $e){
        echo $e->getMessage();
  }