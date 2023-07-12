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
         * @param string $log_text the log text
         */
        public function log_error($log_text): void;

        /**
         * log an action to the 
         * 
         * @param string $log_text the log text
         */

         public function log_action($log_text): void;
 
}