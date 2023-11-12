<?php
  namespace secureDB\contracts\DB;

  /**
   * the database connection interface
   * 
   */
  interface connection {
        
    /**
     * 
     * establish a database connection
     */

     public function connect();

     /**
      * close a database connection 
      *
      */
    public function close();

    /**
     * execute an sql query
     * 
     */

     public function execute(): null | string | object | array;
     /**
      * query a database 
      *
      */
    public function query();

    /**
     * 
     * prepare a sql query
     */

     public function prepare();

     /**
      * 
      * return a single result
      */
    public function fetch_one();

    /**
     * 
     * return multiple results
     */

     public function fetch_all();

     /**
      * 
      * set the fetch mode
      */
     public function set_fetch_mode();
  }
  