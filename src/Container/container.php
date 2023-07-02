<?php
 namespace secureDB\container;

 use secureDB\contracts\Container\container as ContainerContract;

 /**
   * 
   * this is the IoC conatiner for resolving dependencies
   */
 class container implements ContainerContract {

        /**
         * the container instance 
         * 
         */

         private static $instance = null;

         /**
          *  an array of  resolved type
          * @var array ["abstract"]
          */
         
         private $resolved = [];

         

         /**
          * an array of instances 
          * these instances are cached instances
          * which are usually used by more than one service
          * @var array ["$abstract" => instance]
          */

          private $instances = [];

          /**
           * an array of the container's bindings
           *  @var array ["abstract" => ["concrete" => "the concrete implementation" , "shared" => bool]]
           */

           private $bindings = [];

           /**
            * check if an abstract is a shared object 
            *
            */

            /**
             * an array of aliases
             *  @var array [$alias => $abstract]
             */

             private $alias = [];

          /**
           * check if the abstract is a shared abstract
           * 
           */
           public function is_shared($abstract)
           {
                //check if the abstract is present in the instances array
                //or if the shared value of the abstract is true
                return (isset($this->instances[$abstract]) ||
                 (isset($this->bindings[$abstract]) && $this->bindings[$abstract]["shared"] === true));
                 
           }

           /**
            * check if the type is an alias 
            *
            */
           public function is_alias(string $abstract)
           {
              return (isset($this->alias[$abstract]));
           }

           /**
            * return the abstract associated with an alias 
            * 
            */

            protected function get_abstract($alias)
            {
                //check if the alias is present in the alias array
                //if true  it means $alias is really an $alias
                //therefore return the associated abstract
                if(!isset($this->alias[$alias])){
                        return $this->alias[$alias];
                }
                //if false this line will execute, meaning $alias is 
                //not an alias but an abstract therefore return it
                return $alias;
            }
        
            /**
             * check if an abstract has been resolved
             * 
             */

             public function is_resolved($abstract)
             {
                //check if $abstract is an alias if true 
                // get the abstract for the alias
                 if($this->is_alias($abstract)){
                        $abstract = $this->get_abstract($abstract);
                 }
                 return (in_array($this->resolved , $abstract) ||
                      isset($this->instances[$abstract]));
             }

             /**
              * return a closure that will be used to resolve 
              * the abstract
              */

              protected function get_closure($abstract , $concrete)
              {
                 return function($container , $parameters) use($abstract , $concrete)
                 {
                     if($abstract === $concrete){
                        return $container->build($concrete);
                     }
                     return $container->resolve($abstract);
                 };
              }

              /**
               *  check if an abstract has been
               *  bound to the container
               */

               public function bound($abstract)
               {

                //to check if an abstract has been bound we
                // check if the abstract is in the bindings array
                // or in the instances array
                // or if it is an alias
                return (isset($this->bindings[$abstract]) || 
                      isset($this->instances[$abstract]) ||
                       $this->is_alias($abstract));
               }

             /**
              *  return the concrete for an abstract
              *
              */

              protected function get_concrete($abstract)
              {
                //check if the abstract exists or has been 
                // bound to the container
                if(isset($this->bindings[$abstract]))
                {

                }
              }

 }
