<?php
 namespace secureDB\components\DB;

use PDO;
use secureDB\contracts\Container\container;
use secureDB\contracts\DB\connection;

 class mysql_connection implements connection {

        /**
         * 
         * database host
         * @var string $host
         */
        private $host = null;
        /**
         *  database port
         *  @var int $port
         */

         private $port = 3360;
        /**
         * 
         * database username
         * @var string $username
         */

         private $username = null;
         /**
          * the database $password
          * @var string $password
          */
         private $password = null;

         /**
          * 
          * database name
          * @var string $db_name
          */
          private $db_name = null;
         
          /**
           *  holds the instance of the ioc container
           * for resolving dependencies
           * @var object $app 
           */
          private $app = null;
          /**
           * 
           * holds the database configurations
           *  @var array $config
           * 
           */
          private $config = null;

          /**
           * 
           * holds the database connection 
           *       
           */

           private $connection = null;

           /**
            * holds the resullt of a datbase operation 
            *
            */
           private $result = null;

          /**
           * 
           * @param \secureDB\contracts\Container\container $app
           * @param array $config
           */
          public function __construct(container $app , $config)
          {
           $this->app = $app;
           $this->config  = $config;
          }
          /**
           * 
           * parse the configuration
           */

          private function parse_config()
          {
             $this->password = $this->config["db_password"];
             $this->username = $this->config["db_username"];
             $this->host = $this->config["host"];
             $this->db_name = $this->config["db_name"];
             $this->port = $this->config["port"];
          }

          private function get_dns()
          {
            return 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
          }

          public function connect()
          {
                $this->connection = new PDO($this->get_dns() , $this->username , $this->password , ['port' => $this->port]);
                return $this;
          }
          public function get_connection()
          {
            return $this->connection;
          }

          public function query()
          {
                
          }

          public function execute(): null|string|object|array
          {
                return null;
          }

          public function prepare()
          {
                
          }
          public function set_fetch_mode()
          {
                
          }

          public function fetch_all()
          {
                
          }

          public function fetch_one()
          {
                
          }

          public function close()
          {
                
          }
 }
