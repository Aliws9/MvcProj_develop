<?php

namespace System\Database\Traits;


//تعریف متد های نهایی ما و اینکه تعریف قوانین اجرای متد ها به صورت متد چین و پشت سر هم
trait HasMethodCaller
{

     private $allMethods = ['create', 'update', 'delete', 'find', 'all', 'where', 'save', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate'];

     private $allowedMethods = ['create', 'update', 'delete', 'find', 'all', 'where', 'save', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate' , 'count'];



     // اگر نام متد صدا زده وجود نداشت, این دو متد صدا زده میشه
     public function __call($method, $args) {

          return $this->methodCaller($this, $method, $args);

     }

//به صورت استاتیک : $user::find()->where()
     public static function __callStatic($method, $args) {

          $className = get_called_class();
          $instance = new $className;
          return $instance->methodCaller($instance, $method, $args);

     }






     //اجرای متد های ما
     protected function methodCaller($object, $method, $args) {

          $suffix = 'Method';
          $methdoName = $method . $suffix;

          if (in_array($method, $this->allowedMethods))
          {

               //اجرای متد ما
               return call_user_func_array(array($object, $methdoName), $args);

          }

     }

     protected function setAllowMethod($array) {

          $this->allowedMethods = $array;

     }

}