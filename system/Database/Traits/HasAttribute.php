<?php

namespace System\Database\Traits;

// طراحی و مقدار دهی collection
// نتیجه دریافت مقادیر از دیتابیس رو به صورت آرایه در کالکشن مقدار دهی میکنیم
// مقدار دهی این متغیر ها : collection , hidden , cast

// 1_cast : تغیر و اصلاح نوع اطلاعات برای بهینه شدن سیستم. مثلا تبدیل آرایه به رشته برای ذخیره در دیتابیس و بلعکس (castEncode , castDecode) بیشتر در ذخیره سایز های مختلف تصاویر (serialize)
// 2_hidden : فیلد هایی که نیاز نداریم نمایش داده بشن مثلا رمز عبور
// 3_collection : مقادیر دریافتی از دیتابیس به صورت آرایه تو در تو

trait HasAttribute
{



     private function registerAttribute($object, string $attribute, $val) {

          if ($this->inCatsAttributes($attribute) == true)
          {
               $object->$attribute = $this->castDecodeValue($attribute, $val);
          } else
          {
               $object->$attribute = $val;
          }
     }

     protected function arrayToAttributes(array $array, $object = null) {
          if (!$object)
          {
               $className = get_called_class();
               $object = new $className;
          }

          foreach ($array as $attribute => $value)
          {

               if ($this->inHiddenAttributes($attribute) == true)
                    continue;

               $this->registerAttribute($object, $attribute, $value);

          }

          return $object;

     }

     protected function arrayToObjects(array $array) {
          $collection = [];

          foreach ($array as $val)
          {
               $object = $this->arrayToAttributes($val);
               array_push($collection, $object);
          }

          $this->collection = $collection;

     }
     // $sql = "SELECT * FROM";
// $sql = record1 , record2 , record3;
// record1 = name = ali / age = 21 / pass = 123









     private function inHiddenAttributes($attribute) {
          return in_array($attribute, $this->hidden);
     }

     private function inCatsAttributes($attribute) {

          return in_array($attribute, array_keys($this->casts));
     }








     // image = 'serialize' ['80*80'=> 'https://upload/image.png' , '140*240' => 'https://upload/image2.png']
     private function castDecodeValue($attributeKey, $val) {
        
          if ($this->casts[$attributeKey] == 'array' || $this->casts[$attributeKey] == 'object')
          {
               return unserialize($val);
          }
          return $val;

     }

     private function castEncodeValue($attributeKey, $val) {

          if ($this->casts[$attributeKey] == 'array' || $this->casts[$attributeKey] == 'object')
          {
               return serialize($val);
          }
          return $val;

     }



     private function arrayToCastEncodeValue($vals) {

          $newArray = [];

          foreach ($vals as $attr => $value)
          {

               $this->inCatsAttributes($attr) == true ? $newArray[$attr] = $this->castEncodeValue($attr, $value) :
                    $newArray[$attr] = $value;
          }

          return $newArray;

     }


}
