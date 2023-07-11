<?php
 namespace secureDB;

use secureDB\components\config_parser\parser;
use secureDB\components\Template_engine\template_engine;
use secureDB\contracts\Container\container;
use secureDB\contracts\TemplateEngine\Template_engine as Template_engineContract;

/**
 * ______________________________________________
 *  This is where all the core components of the 
 * application is loaded and all dependencies 
 * registered
 *  @author julius George <julius.george.hack@gmail.com>
 * _______________________________________________
 */

  class app {

   /**
    * Application version
    * @var int
    */
     const  version = '1.0.0';

     /**
      * the path to the config path 
      *
      * @var string 
      */
     private $config_path = '{ROOT}/config/';

     /**
      * an instance of the dependency injection
      * container
      *
      */

     private  $container = null;

     /**
      * the template engine 
      *
      */

     private $template_engine = null;

     /**
      * the config parser
      *
      */
     private $config_parser = null;


     public function __construct(container $container)
      {
        $this->container = $container;
        $this->register_container_bindings();
        //instantiate the tmeplate engine and bind
        // the {ROOT} template to the engine
        $this->instantiate_template_engine();
        $this->bind_root();
        $this->instantiate_config_parser();
        $this->parse_config();
      }

     /***
      * instantiate all dependencies 
      */
      protected function instantiate_config_parser()
      {
       $this->config_parser = $this->container->resolve(parser::class , ['path' =>$this->template_engine->get('config_path')]);
        }

      /**
       * register all the container's bindings
       * 
       */

       protected function register_container_bindings()
       {
          $this->container->singleton(Template_engineContract::class , template_engine::class);
          $this->container->bind(parser::class);
       }

       /**
        * instantiate the template engine
        * the config need it to be above to resolve {ROOT} in the config
        * path [{ROOT}\config]
        */

        protected function instantiate_template_engine()
        {
          $this->template_engine = $this->container->get(Template_engineContract::class);   
        }

       /**
        * parse the file and add the configurations to 
        * the container
        */
       protected function parse_config()
       {
         $configurations = $this->config_parser->get_configurations();

         foreach($configurations as $configuration => $value)
         {
          $this->container->bind($configuration , function ($container) use ($value){
            return $value; }
          );
         }
            var_dump($this->container->get('connections')["read"]);

       }

       /**
        * bind the root to the container
        * @see components\Template_engine\template_engine
        */
       protected function bind_root()
       {
      
         $root = str_replace('\\', '/' , dirname(__DIR__));
         $this->template_engine->bind('ROOT' , $root);
         //add the config path to the template engine
         $this->template_engine->add('config_path', $this->config_path);
       }

       
  }

  