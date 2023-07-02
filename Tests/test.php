<?php
 
 class container {

        public function __construct(String $parameter)
        {
                var_dump($parameter);
        }

 }
 $r = new ReflectionClass(container::class);

 $p = $r->getConstructor()->getParameters()[0];
 var_dump($p->getType());

 