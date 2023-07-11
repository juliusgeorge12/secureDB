<?php
 namespace secureDB\contracts\Logger;

 /**
  * 
  * @author julius George <julius.george.hack@gmail.com>
  */

 interface Logger {

        /**
         *  log an error to the error;
         * 
         */
        public function log_error(): void;

        /**
         * log an action to the 
         * 
         */

         public function log_action(): void;
 
}