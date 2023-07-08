<?php
 namespace secureDB;

/**
 * ______________________________________________
 *  This is where all the core components of the 
 * application is loaded and all dependencies 
 * registered
 * 
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
     private $config_path = '{ROOT}/config';

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


  }