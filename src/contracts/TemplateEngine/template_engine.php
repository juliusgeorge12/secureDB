<?php
  namespace secureDB\contracts\TemplateEngine;

  /**
   *  
   * @author julius George <julius.george.hack@gmail.com>
   */
  interface Template_engine {

        /**
         * add a template to the engine
         * 
         */
        public function add(string $name , $value);

        /**
         * return the template with the values 
         * replaced in it.
         * 
         * @param string $template the name of the template
         * @param mixed $bindings an associative array of binds and values
         * [$binding => $value]
         */

         public function get(string $template , $bindings);

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
         public function bind(string $variable , string $value);
        
  }