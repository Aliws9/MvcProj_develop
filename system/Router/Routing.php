<?php
namespace System\Router;
use ReflectionMethod;
use System\Config\Config;
use System\Auth\Auth;
use System\Session\Session;

// ---------------- تجزیه تحلیل مسیر جاری (مسیری که کاربر وارد میکنه) و مقایسه با مسیر های رزرو شده -----------------
class Routing
{
     private $current_route;
     //مسیری که کاربر وارد کرده (مسیر جاری)
     private $routes;
     // مسیر های رزرو شده
     private $method_field;
     // get_post تشخیص نوع درخواست هر ریکویستی که کاربر وارد میکنه مثل 
     private $values = [];
     // مقادیر و ورودی های مورد نیاز برای متد گرفته شده از مسیر جاری


     public function __construct() {
        
        //   $this->current_route = explode('/', trim(CURREN_ROUTE, '/'));
          $this->current_route = explode("/", Config::get('app.CURRENT_ROUTE'));
          // array_filter($this->current_route);
          $this->method_field = $this->methodField();
          
          global $routes;
          $this->routes = $routes;

     }

     // بررسی نهایی وجود کلاس و متد و پارامتر ها بعد از بررسی اینکه ادرس های رزرو شده و ادرس وارد شده برابر هستند.
     // یعنی بعد از مقایسه ادرس وارد شده با ادرس های رزرو شده, اگر ادرس وارد شده کاملا برابر بود با یکی از ادرس های رزرو شده که در متد مچ این بررسی اتفاق میفتاد, کلاس و متد و مقدار ها رو ارسال میکنه به متد ران
     public function run() {

          $match = $this->match();
          if (empty($match))
          {
               // var_dump('hi');
               return $this->error404();
          }

          $classPath = $match['class'];
        //   $path = BASE_DIR . '/app/Http/Controllers/' . $classPath . '.php';
          $path = Config::get('app.BASE_DIR') . '/app/Http/Controllers/' . $classPath . '.php';

          if (!file_exists($path))
          {

               $this->error404();
          }

          //استخراج متد های کلاس بعد از ساخت شی از اون کلاس و بررسی وجود اون متد
          $class = "\App\Http\Controllers\\" . $match['class'];
          $class_ob = new $class();

          if (method_exists($class_ob, $match['method']))
          {

               $reflection = new ReflectionMethod($class, $match['method']);
               $parameterCount = $reflection->getNumberOfParameters();

               if ($parameterCount <= count($this->values))
               {

                    call_user_func_array(array($class_ob, $match['method']), $this->values);
                    // var_dump($class_ob,$match['method']);

               } else
               {

                    $this->error404();
               }

          } else
          {

               $this->error404();
          }


     }


     // دریافت مسیر مقایسه شده از متد پایین که اگر ترو بود یک کاری انجام بده اگر درست نبود متد 404 فراخانی بشه
     // category/edit/{id} => reserved
     // category/edit/25   => current_route
     public function match() {
          $reservedRoutes = $this->routes[$this->methodField()];

          foreach ($reservedRoutes as $reservedRoute)
          {

               if ($this->compare($reservedRoute['url']) == true)
               {
                    return ['class' => $reservedRoute['class'], 'method' => $reservedRoute['method']];

               } else
               {
                    $this->values = [];
               }

          }

          return [];

     }

     // مقایسه کامل مسیر رزرو شده با مسیر وارد شده توسط کاربر و ارسال پاسخ به متد بالا (مچ)
     // category/edit/{id} => reserved
     // category/edit/25   => current_route
     private function compare($reservedRouteUrl) {

          // part 1
          if (trim($reservedRouteUrl, '/') === '')
          {

               if (trim($this->current_route[0], '/') === '')
               {

                    return true;

               } else
               {
                    return false;
               }
          }

          //part2
          $reservedRouteUrlArray = array_filter(explode('/', $reservedRouteUrl));

          if (sizeof($reservedRouteUrlArray) != sizeof($this->current_route))
          {

               return false;
          }

          //part 3---
          foreach ($this->current_route as $key => $currentRouteElement)
          {

               $reservedRouteUrlElement = $reservedRouteUrlArray[$key];

               if (substr($reservedRouteUrlElement, 0, 1) == "{" && substr($reservedRouteUrlElement, -1) == "}")
               {

                    array_push($this->values, $currentRouteElement);

               } elseif ($reservedRouteUrlElement != $currentRouteElement)
               {

                    return false;

               }

          }
          return true;

     }

     public function error404() {

          http_response_code(404);
          // var_dump($this->current_route) . '<br>';
          include __DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . '404.php';
          exit;

     }

     // بررسی نوع متد در آدرس وارد شده توسط کاربر
     public function methodField() {

          // تبدیل get و post به حروف کوچیک
          $method_field = strtolower($_SERVER['REQUEST_METHOD']);

          // اگر نوع پست بود باید تشخیص بدیم که این پست آپدیت هست یا دلیت. با یک اینپوت هیدن به اسم _متد
          if ($method_field == 'post')
          {

               if (isset($_POST['_method']))
               {

                    if ($_POST['_method'] == 'put')
                    {

                         $method_field = 'put';

                    } elseif ($_POST['_method'] == 'delete')
                    {

                         $method_field = 'delete';

                    } else
                    {
                         $method_field = 'post';
                    }

               }

          }
          // echo $method_field;
          return $method_field;
     }




}