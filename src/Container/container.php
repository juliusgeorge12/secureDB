<?php
 namespace secureDB\container;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use secureDB\contracts\Container\container as ContainerContract;
use TypeError;

 /**
   * 
   * this is the IoC conatiner for resolving dependencies
   * @author Julius George <julius.george.hack@gmail.com>
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
             * an array of aliases
             *  @var array [$alias => $abstract]
             */

             private $alias = [];

             /**
              * the parameter override stack 
              * @var array
              */
            
              private $param_override = [];

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
            * alias a string to an abstract 
            * @param string $abstract
            * @param string $alias
            *
            * @return object C:\project\secureDB\Tests\test.php
            */

            public function alias(string $abstract, string $alias)
            {
               $this->alias[$alias] = $abstract;
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
               if(isset($this->alias[$alias])){
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
                 return (in_array($abstract , $this->resolved) ||
                      isset($this->instances[$abstract]));
             }

             /**
              * return a closure that will be used to resolve 
              * the abstract
              */

              protected function get_closure($abstract , $concrete)
              {
                
                 return function($container , $parameters = []) use($abstract , $concrete)
                 { 
                     if($abstract === $concrete){
                          return $container->build($concrete);
                     }
                     //check if the concrete is a string
                     if(is_string($concrete)){
                        //get a reflection of the string
                        try {
                        $own_reflector = new ReflectionClass($concrete);
                        // check if it is instantiable i.e it is a class
                       // return an instance of the class 
                       // if it is not a class return the string
                      if($own_reflector->isInstantiable()){ return new $concrete; }

                        } catch(Exception $e){
                           return $concrete;
                          }
                        }                               
                     return $container->resolve($abstract , $parameters);
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
               return isset($this->bindings[$abstract]) || 
                        isset($this->instances[$abstract]) ||
                        $this->is_alias($abstract);
                       
               }

            /**
             * return all of the container's bindings 
             *
             */

              public function get_bindings()
              {
                return $this->bindings;
              }

             /**
              *  return the concrete for an abstract
              *
              */

              protected function get_concrete($abstract)
              {
                //check if the abstract exists or has been 
                // bound to the container
                if($this->bound($abstract))
                {
                   return $this->bindings[$abstract]["concrete"];
                }
                throw new not_found_exception("the abstract $abstract does not exist/has not been bound to the container");
              }

              /**
               * bind an abstract to the container
               * 
               */

               public function bind($abstract, $concrete = null , $shared = false)
               {
                 // throw an Exception if the abstract has already been bound
                 if($this->bound($abstract)){
                        throw new Exception("the abstract {$abstract} has alreay been bind to the container");
                 }
                 //check if the concrete is null if yes/true
                 // it means the abstract is also the closure
                 if(is_null($concrete)){
                        $concrete = $abstract;
                 }

                 //check if the concrete is not a closure if true
                 //return a closure

                 if(! $concrete instanceof Closure){
                        //check if the concrete is a string if not
                        // throw an TppeError b
                        if(!is_string($concrete)){
                           throw new TypeError(self::class . '::bind(): Argument #2 ($concrete) must be of type Closure|string|null');
                        }
                        $concrete = $this->get_closure($abstract , $concrete);
                 }
                 //bind the abstract to the container
                 //compact('concrete' , 'shared') does the same thing
                 //as  ["concrete"=> $concrete , "shared" => $shared]
                 $this->bindings[$abstract] = compact('concrete' , 'shared');

               }

               /**
                * bind a shared abstract to the container 
                *
                */

                public function singleton($abstract , $concrete = null)
                {
                        $this->bind($abstract , $concrete , true);
                }

               
               /**
                *  return an instance of an abstract
                * @param $abtract the abstract to resolve
                * @param $parameters list of parameters to be 
                * pass as an argument to the instance
                * @return mix
                */

                public function make($abstract, $parameters = [])
                {    
                     return   $this->resolve($abstract , $parameters);
                }

               /**
                * check if the concrete is buildable
                *
                * @param mixed $concrete
                * @param string $abstract
                * 
                * @return bool
                */

                protected function is_buildable($concrete , string $abstract)
                {
                  // the concrete is buildable if the concrete 
                  // is same type and value as the abstract 
                  // or it is a closure

                  return $concrete === $abstract || ($concrete instanceof Closure);
                 
                }

               /**
                *  resolve a concrete
                * 
                */

                public function resolve($abstract , $parameters = [])
                {
                  // check the alias , the get_abstract method will
                  // check if the abstract is an alias or an abstract
                  // if it is an abstract it will return the 
                  // abstract for the alias else it will return the
                  // abstract
                  $abstract = $this->get_abstract($abstract);
                  
                  //get the concrete to be resolve
                  $concrete = $this->get_concrete($abstract);

                  //check if the abstract is a singleton
                  //if it is a singleton we will check if it has 
                  //been resolve if yes, we will just return 
                  //the cached instance
                  if(isset($this->instances[$abstract])){
                     return $this->instances[$abstract];
                  }
                  
                  //add the parameters to the parameter override
                  $this->param_override[] = $parameters;
                  // check if the concrete is buildable
                 // and build it if it is                                              

                  if($this->is_buildable($concrete , $abstract)){
                      $object = $this->build($concrete);
                   }
                   else {
                     //make the concrete and build it
                    $object = $this->make($concrete);
                   } 
                   // cached the resolved concrete if
                   // it is a shared object

                   if($this->is_shared($abstract)) {
                     $this->instances[$abstract] = $object;
                   }
                   // add the abstract to the 
                   //  resolved array and return the object

                   $this->resolved[] = $abstract;
                    array_pop($this->param_override);
                   return $object;
                }

                /**
                 * build the concrete
                 * 
                 * @param mixed $concrete
                 *
                 * @return mixed
                 */

                 protected function build($concrete) 
                 { 
                    // if the type is a closure we call the
                    // function and pass the container to it as
                    // an argument so it can be used to resolve 
                    // more object
                    if($concrete instanceof Closure){
                    
                      return $concrete($this , $this->get_last_parameter_override());
                    }
                    
                    try {
                     // if it is a class or an interface,
                     // we are going to use the built-in
                     // ReflectionClass object to automatically
                     // resolve it and it's dependencies and
                     // inject it into it.
                      $reflector = new ReflectionClass($concrete);
                    } catch(ReflectionException $e){
                     throw new Exception("Target class [$concrete] does not exist");
                    }

                    // check if the type is not instantiable and throw 
                    // an exception if it is not instantiable
                    if(!$reflector->isInstantiable()){
                     throw new Exception("the type [$concrete] can not be instantiated");
                    }

                     // get the constructor of the class
                    // it will return null if there is no 
                    // constructor
                    $constructor = $reflector->getConstructor();


                    // if there is no constructor
                    // that means the class does not
                    // have a dependency hence ,
                    // instantaite it and return the instance

                    if(is_null($constructor)){
                      return new $concrete;
                    }

                    // get the constructor parameters
                    $dependencies = $constructor->getParameters();

                   
                    //resolve the dependencies and return the instances
                    try {
                     $instances = $this->resolve_dependencies($dependencies);

                    } catch(Exception $e){
                     throw $e;
                    }
                    
                    // use the newInstanceArgs method of
                    // the reflector object to instantiate the
                    // class injecting it's dependencies.
                    return $reflector->newInstanceArgs($instances);
                 }

                /**
                 * resolve the dependencies pass to it.
                 * @param array $dependencies
                 * @return array the arrray of resolved dependencies
                 * 
                 */

                public function resolve_dependencies(array $dependencies){

                  //an array to store resolved dependencies

                  $results = [];

                  //loop through thr array of $dependencies and resolve them
                  //one by one
                  foreach($dependencies  as $dependency){
                     
                 // If the dependency has an override for this particular build we will use
                 // that instead as the value. Otherwise, we will continue with this run
                 // of resolutions and let reflection attempt to determine the result.
                    if ($this->has_parameter_override($dependency)) {
                     
                     $results[] = $this->get_parameter_override($dependency);

                       continue;
                      }
                     //get the class parameter name, if it is null
                     //it means the dependency is a string or some other primitive type
                     $result = is_null($this->get_class_param_name($dependency))
                               ? $this->resolve_primitive($dependency):
                                 $this->resolve_class($dependency);
                                
                   //add the result to the result array
                     $results[] = $result;
                  }
                  //return the resolved dependencies
                   return $results;
                }

                /**
                 * get the class parameter name.
                 * 
                 * 
                 */

                 protected function get_class_param_name($parameter){
                   
                  //get the type hint of the parameter

                  $type = $parameter->getType();
                 

                  //check if the type hint is an instance of 
                  //ReflectionNamedType or is a builtin type
                  //if true get the name and return it
                  //else null

                  if(! $type instanceof ReflectionNamedType || $type->isBuiltin()){
                     return null;
                  }
                  //get the parameter name

                  $name = $type->getName();
                    
                  //check if there is a declaring class
                  // and get the name

                  if(!($class = $parameter->getDeclaringClass())){
                     if ($name === 'self') {
                          return $class->getName();
                    }
        
                    if ($name === 'parent' && $parent = $class->getParentClass()) {
                     return $parent->getName();
                    }
                  }
                   return $name;

                 }
               
                /**
                 * resolve a primitive type
                 * @param \ReflectionParameter $parameter
                 */

                 protected function resolve_primitive(ReflectionParameter $parameter){
                    
                  try {
                    
                     $concrete = $this->get_concrete($parameter->getName());
                     
                  } catch (not_found_exception $e){
                      throw (new Exception($e->getMessage()));
                  }
                  //check if the concrete is an instance of closure
                  //call it and return it if it does
                    if ($concrete instanceof Closure){
                      return $concrete($this);
                      }
                   else { 
                    //check if there is a default value for the parameter
                    //and return it
                     if($parameter->isDefaultValueAvailable()){
                        return $parameter->getDefaultValue();
                     }
                     throw new unresolvable("the type [$parameter] can't be resolved");
                     }
                  
                }
                 
                /**
                 * resolve a class hinted dependency
                 * 
                 * @param \ReflectionParameter $parameter
                 * @return mixed
                 */

                 protected function resolve_class(ReflectionParameter $parameter)
                 {
                  
                     try {
                        return $this->make($this->get_class_param_name($parameter));
                       
                     }
                     catch(Exception $e){
                       if($parameter->isDefaultValueAvailable()){
                        return $parameter->getDefaultValue();
                       }
                       throw $e;
                     }
                     
                 }

            /**
             * Determine if the given dependency has a parameter override.
             *
             * @param  \ReflectionParameter  $dependency
             * @return bool
             */
             protected function has_parameter_override($dependency)
            {
               return array_key_exists(
                $dependency->name, $this->get_last_parameter_override()
              );
           }

               /**
                * Get a parameter override for a dependency.
                *
                * @param  \ReflectionParameter  $dependency
                * @return mixed
              */
    
              protected function get_parameter_override($dependency)
              {
                return $this->get_last_parameter_override()[$dependency->name];
                }


                 /**
                  * get the last parameter override 
                  * 
                  * @return mixed
                  */

                  protected function get_last_parameter_override()
                  {
                     return count($this->param_override) ? end($this->param_override) : [];
                  }

                /**
                 * 
                 * check if the abstract exist in the container
                 * @param $id the abstract to check
                 */

                 public function has($id)
                 {
                        return $this->bound($id);
                 }

                 /**
                  * return the instance of the abstract
                  * @param $abstract 
                  *
                  */

                 public function get($id)
                 {
                        try {
                           return $this->resolve($id);
                        } catch(Exception $e){
                        if ($this->has($id)){
                           throw $e;
                         } else {
                           throw (new not_found_exception("the abstract [$id] does not exist"));
                         }
                        }
                       
                 }
            
                /**
                 * set and return the container instance
                 * 
                 */
                public static function set_instance(ContainerContract $container)
                {
                        return static::$instance = $container;
                }

                /**
                 * return the container instance
                 * 
                 */

                 public static function get_instance()
                 {
                        if(is_null(static::$instance)){
                                static::$instance = new static;
                        }
                        return static::$instance;
                 }

                 /**
                  * flush the container from it's bindings and 
                  * clear all cached instances, it kinda reset the
                  * container, you might want to call this method
                  * after your application lifecycle has ended to
                  * free resources.
                  */

                  public function reset() 
                  {
                     $this->alias = [];
                     $this->instances = [];
                     $this->bindings = [];
                  }

                  /**
                   * does the same thing as reset
                   * it is an alias name for reset
                   */

                   public function flush()
                   {
                     $this->reset();
                   }


 }
