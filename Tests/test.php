<?php

use secureDB\container\container;

require_once  dirname(__DIR__) . "\\vendor\\autoload.php";


$container = new container;

$container->bind("classname" , function($container){
        return "hello";
});

 $container->singleton('george' , function($container){
        echo "my name is george";
 });
var_dump($container->bound('classname'));
var_dump($container->is_shared('george'));
