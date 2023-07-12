<?php
 namespace secureDB;

use Exception;
use secureDB\components\config_parser\parser;
use secureDB\components\Logger\Logger;
use secureDB\components\Template_engine\template_engine;
use secureDB\contracts\Container\container;
use secureDB\contracts\Logger\Logger as LoggerContract;
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

     /**
      * the configurations in the config file 
      *
      */

      private $configurations = null;


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
        $this->register_configurations();
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
          $this->container->bind(LoggerContract::class , Logger::class);
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
         $this->configurations = $configurations;
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

        /**
         * register the various configurations to the container
         * so that it can be resolved from the container
         * anywhere it is needed
         */

         protected function register_configurations()
         {
            $this->register_debug_mode();
            $this->register_log_path();
            $this->register_prepared_statement_mode();
            $this->register_driver();
         }

         /**
          * register the debug mode to the container 
          *
          */

          protected function register_debug_mode()
          {
            if(!isset($this->configurations["debug_mode"])){
              $debug_mode = 4;
            } else {
              $debug_mode = $this->configurations["debug_mode"];
             }
              $this->container->bind('debug_mode' , function($c) use ($debug_mode){
                return $debug_mode;
              });
          }
          /**
          * register the log path to the container 
          *
          */

          protected function register_log_path()
          {
            if(isset($this->configurations["log_path"]) && $this->configurations["log_path"] != ' ')
            {
              $log_path = $this->configurations["log_path"];
               }
            else if(isset($this->configurations["default_log_path"]) && ($this->configurations['default_log_path']) != ' '){
              $log_path = $this->configurations["default_log_path"];
            } else 
            {
              $log_path = "{ROOT}/log";
             }
            $this->template_engine->add('log_path' , $log_path);
            $log_path = $this->template_engine->get('log_path');
            $this->container->bind('log_path' , function($c) use ($log_path)
          {
            return $log_path;
          });
         }

          /**
          * register the prepared_statement_enabled flag
          * to the  container
          */

          protected function register_prepared_statement_mode()
          {
            if(isset($this->configurations["prepared_statement"]) && $this->configurations["prepared_statement"] != ' ')
            {
              $prepared_statement = $this->configurations["prepared_statement"];
               }
             else 
            {
              $prepared_statement = false;
             }
            $this->container->bind('prepared_statement_enable' , function($c) use ($prepared_statement)
          {
            return $prepared_statement;
          });

          }

         /**
          * register the sql driver type to the 
          * container
          */

          protected function register_driver()
          {
            if(isset($this->configurations["driver"]) && $this->configurations["driver"] != ' ')
            {
               $driver = $this->configurations["driver"];
               }
            else if(isset($this->configurations["default_driver"]) && ($this->configurations['default_driver']) != ' '){
              $driver = $this->configurations["default_driver"];
            } else 
            {
              $driver = "mysql";
             }
             $this->container->bind('driver' , function($c) use ($driver)
          {
            return $driver;
          });
         }

      

       
  }

  