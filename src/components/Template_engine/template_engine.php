<?php
 namespace secureDB\components\Template_engine;

use Exception;
use secureDB\contracts\TemplateEngine\Template_engine as TemplateEngineContract;

/**
 * The template engine
 * 
 * @author Julius George <julius.george.hack@gmail.com>
 */
 class template_engine implements TemplateEngineContract {
   
        /**
         * The template 
         * 
         */
        private $template = [];

        /**
         * an array of $variable=>$value pair
         * 
         */
        private $bindings = [];

        
        /**
         *  add a template to the emgine
         * 
         */

        
         public function add(string $name, $value)
         {
                if(isset($this->template[$name])){
                        throw new Exception("the template {$name} already exists");
                }
                $this->template[$name] = $value;
         }

         /**
          * bind a value to the template that you want
          *  to be will be present universally and shared
          * by an other template
          * @param string $variable the varaible/name you
          * want to bind to the engine e.g ROOT 
          * take note, don't use {ROOT} as the name
          * @param string $value this is the value that
          * would be substituted for $varaible, that is to 
          * say anywhere there is {$variable} in the template
          * it would be replaced by $value
          */

          public function bind(string $variable, string $value)
          {
                $this->bindings[$variable] = $value;
          }
        
          /**
           * replace the $variable with the $value
           *  and return the result
           * 
           * @param string $temp the template string
           * 
           * @param array $replacement [$search => $replacement]
           */
        protected function replace(string $temp , $replacement)
        {
               return str_replace($replacement["search"] , $replacement["replacement"]  , $temp);
        }

        /**
         * 
         * make  the replacement and search
         * to fit the format
         * ["replacement" => [replacemnts] , "search" => [seaches]]
         * 
         * @param array $replacement 
         * @param array $searches
         * 
         */

         protected function compile(array $replacement , array $searches)
         {
                return ["replacement" => $replacement , "search" => $searches];
         }

         /**
          * wrap the search with {} 
          * 
          * @param string $search
          * 
          * @return string {$search}
          */
        protected function normalize(string $search)
        {
                return '{' . $search . '}';
        }

        /**
         * get the name  value pair from
         * the input of [$name => $value]
         * and wrap the name within {}
         * this is what would be used 
         * to do the search in the template
         * string
         */

         public function get_searches(array $searches)
         {
               if(empty($searches)){
                throw new Exception("array of searches can't be empty");
               }
             $search = array_map(function ($name){ 
            return $this->normalize($name); } , $searches);
            return $search;
         }
      
        /**
         * return the template with the values 
         * replaced in it.
         * 
         * @param string $template the name of the template
         * @param mixed $bindings an associative array of binds and values
         * [$binding => $value]
         */
         public function get(string $template, $bindings = [])
         {
                if(!$this->exists($template)){
                        throw new Exception("the template [{$template}] does not exist in the engine");
                }
                //get the template
                $template = $this->template[$template];
                $merged = $this->merge($bindings);
                $variables = array_keys($merged);
                //convert all the variables to {variable} format
                // this is also the search value
                $normilized_variables = $this->get_searches($variables);
                //get all the values that is to be replace into
                // the template
                $values = array_values($merged);
                $compiled = $this->compile($values , $normilized_variables);
                return $this->replace($template , $compiled);
         }

         /**
          * check if there a value is present in the binding
          * if true unset it from the input and merge the input
          * and the binds 
          * @param array $bindings the input bindings
          */

          protected function merge(array $bindings){
             $bind_variables = array_keys($this->bindings);
             foreach($bind_variables as $bind_variable){
                if(array_key_exists($bind_variable ,$bindings)){
                        unset($bindings[$bind_variable]);
                }
             }
             return array_merge($this->bindings , $bindings);
          }

        /**
         * 
         * check if the template exists
         * 
         * @param string $name the template anme 
         */

         protected function exists(string $name)
        {
                return array_key_exists($name , $this->template);
        }

       


 }