<?php
 namespace secureDB\components\Logger;

use secureDB\contracts\Logger\Logger as LoggerLogger;

 /**
  * The logger class
  * 
  * @author julius George <julius.george.hack@gmail.com>
  */

  class Logger implements LoggerLogger {

         const error_file_name = 'error.log';

         const action_file_name = 'action.log';

         /**
          * the log path 
          *
          */
          private $log_path = null;

          public function __construct(string $path)
          {
                $this->set_path($path);
          }

          /**
           * set the log path
           * 
           */
          
           public function set_path($path)
           {
                $path = (substr($path , strlen($path) - 1) != '/') ? $path . '/' : $path;
                $this->log_path = $path;
           }

         /**
          *  return the timestamp
          *
          */

          protected function get_time()
          {
                return date('d-m-Y h:i:sa' , time());
          }


         /**
          * return the full path to the action 
          * log file 
          *
          */
          protected function get_action_log_path()
          {
             return $this->log_path . $this::action_file_name;
          }

        /**
         *  return the full path to the
         *  error log file
         */

         protected function get_error_log_path()
         {
                return $this->log_path . $this::error_file_name;
         }

        /**
         *  log an error to the error log file;
         * 
         */
        public function log_error($log_text): void
         {
          //the path to the error log file
           $log_path  = $this->get_error_log_path();
           $log = $this->format_log($log_text);
           $this->log($log_path , $log);
         }

        
        /**
         * log an action to the log file
         * 
         */

         public function log_action($log_text): void 
         {
          //the path to the action log
          $log_path = $this->get_action_log_path();
          $log = $this->format_log($log_text);
          $this->log($log_path , $log);
         }

         /**
          *  open the file and append the content
          * of the log to it
          *
          */

          protected function log($file , $content)
          {
                $stream = fopen($file , 'a');
                fwrite($stream , $content , strlen($content));
                fclose($stream);
          }

          /***
           * set the log text into a format that is
           * suitable for logging
           */

           protected function format_log($log_text)
           {
                return "[{$this->get_time()}] $log_text \r\n";
           }

  }