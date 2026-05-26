<?php

namespace System\Router\Web;

// ------------ تجزیه تحلیل مسیر های رزرو شده ----------------
// ------------ و وارد کردن مقادیر مورد نیاز مثل کلاس - متد - نوع درخواست (گت - پست - دلیت - پوت) و افزودن به ارایه سراسری روت ------------------
class Route
{
     public static function get($url, $executeMethod, $name = null) {
          // var_dump($url,$executeMethod,$name);
          $executeMethod = explode('@', $executeMethod);

          $class = $executeMethod[0];
          $method = $executeMethod[1];

          global $routes;

          array_push($routes['get'], array('url' => trim($url, "/ "), 'class' => $class, 'method' => $method, 'name' => $name));

     }

     public static function post($url, $executeMethod, $name) {

          $executeMethod = explode('@', $executeMethod);

          $class = $executeMethod[0];
          $method = $executeMethod[1];

          global $routes;

          array_push($routes['post'], [

               'url'    => trim($url, "/ "),
               'class'  => $class,
               'method' => $method,
               'name'   => $name

          ]);


     }

     public static function put($url, $executeMethod = null, $name) {

          $executeMethod = explode('@', $executeMethod);

          $class = $executeMethod[0];
          $method = $executeMethod[1];

          global $routes;

          array_push($routes['put'], [

               'url'    => trim($url, "/ "),
               'class'  => $class,
               'method' => $method,
               'name'   => $name

          ]);

     }


     public static function delete($url, $executeMethod = null, $name) {

          $executeMethod = explode('@', $executeMethod);

          $class = $executeMethod[0];
          $method = $executeMethod[1];

          global $routes;

          array_push($routes['delete'], [

               'url'    => trim($url, "/ "),
               'class'  => $class,
               'method' => $method,
               'name'   => $name

          ]);

     }

}

