<?php
namespace secureDB\components\config_parser;

use Exception;

 class parser {

        /**
         * the path to the json config file
         * 
         */
        private $path;

        /**
         * the parsed json
         * 
         */

         private $parsed = null;

         public function __construct(string $path)
         {    
                $this->path = $path;
                $this->parse();
         }

         /**
          * fetch the config.json file
          *
          */
        
         protected  function  fetch_config_file()
          {
               $len = strlen($this->path);
               if(substr($this->path , $len - 1) != "/")
               {
                $this->path .= "/"; 
               }
                $file = $this->path . "config.json";
              if(!file_exists($file)){
                throw new Exception("The configuration file is missing, the file [$file] is missing");
            }
            return file_get_contents($file);
          }
        
          /**
           * parse the json file
           * 
           */

           protected function parse()
           {
                $json = $this->fetch_config_file();
                $this->parsed = json_decode($json , true);
                if(!$this->parsed){
                        throw new Exception("the config.json file in [{$this->path}] is not a valid json file");
                }
           }

           /**
            *  get a config directive
            * 
            */
            public function get($id)
            {  
                  if(!isset($this->parsed[$id])){
                        throw new Exception("the entry [$id] is not found, seems it is not in the json file");
                }
                return $this->parsed[$id];
            }
            /**
             * return the configurations
             * 
             */
            public function get_configurations()
            {
                return $this->parsed;
            }
 }