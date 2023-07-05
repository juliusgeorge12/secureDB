<?php
  namespace secureDB\contracts\Container;

 
  interface container {

        /**
         * return all the container's bindings
         */

         public function get_bindings();
         
       /**
        * check if the container has the type
        * 
        */ 
       public function has($id);

       /**
        * check if the abstract type has being bound to the container 
        *
        */

       public function bound($abstract);

       /**
        * return a concrete for the abstract
        * 
        */

        public function make($abstract , $parameters);
       
        /**
        * resolve the abstract
        * 
        */

        public function resolve($abstract , $parameters);

        /**
         * register a binding to the container
         * 
         */

         public function bind($abstract , $concrete = null, $shared = true);

        /**
         * check if the supplied string is an alias
         * 
         */

         public function is_alias(string $name);

         /**
          * alias a string to an abstract type 
          * @param string $abstract
          * @param string $alias
          *
          * @return object
          */
         
          public function alias(string $abstract , string $alias);

        /**
         * determine if the the given abstract has been resolved
         * 
         */

         public function is_resolved($abstract);

        /**
         * check if a given type is shared
         * 
         */

         public function is_shared($abstract);

        /**
         * register a shared binding to the container
         * 
         */

         public function singleton($abstract , $concrete);

         /**
          * return a concrete or binding for an abstract type
          * the  abstract can be a string 
          *
          */

        public function get($id);

        /**
         * set the container instance
         * 
         */

         public static function set_instance(container $container);

         /**
          * get the container instance 
          *
          */
        
         public static function get_instance();

        /**
         * flush the container from it's bindings and 
         * clear all cached instances, it kinda reset the
         * container, you might want to call this method
         * after your application lifecycle has ended to
         * free resources.
         */

         public function reset();
        
        /**
         * does the same thing as reset it is an alias name for reset
         * 
         */

         public function flush();
                  
   
  }