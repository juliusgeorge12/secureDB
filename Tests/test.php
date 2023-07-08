<?php

use secureDB\container\container;

require_once  dirname(__DIR__) . "\\vendor\\autoload.php";

 interface animal_interface {
 public function message();
 }

 interface action_interface {
   public function start(string $message);
 }

 class dog implements animal_interface{
        private $name = "Dog";

        public function message(){
                return "this is a {$this->name} \r\n";
        }
 }

 class dog_action implements action_interface {
        public function start(string $message)
        {
                echo $message;
        }
 }


 class animal {

   private $action;

   private $name;

   public function __construct(animal_interface $name , action_interface $action) {

        $this->name = $name;
        $this->action = $action;
   }

   public function action()
   {
        $this->action->start($this->name->message());
   }
 }
class monkey implements animal_interface {
        public function message(){
                echo "i am a monkey";   
        }
}

 $container = new container;
  try {
 $container->bind('animal' , animal::class);
 $container->bind('greeting' , 'hello, how are you doing');
 $container->bind('animal_interface' , dog::class);
 $container->bind('action_interface' , dog_action::class);
 $container->get('animal')->action();
 echo $container->get('greeting');

  } catch(Exception $e){
        echo $e->getMessage();
 }
 