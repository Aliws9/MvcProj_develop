<?php

namespace System\Database\ORM;

use \System\Database\Traits\HasCRUD;
// عملیات اصلی و کویری های اصلی مثل select insert و...

use \System\Database\Traits\HasQueryBuilder;
// اتصال قسمت های اجرایی مثل قرار دادن دستورات اس کیو ال یا قرار دادن where و در نهایت اجرا و execute کردن دستور دیتابیس

use \System\Database\Traits\HasMethodCaller;
// رگردوندن اسم متد های عملیاتی

use \System\Database\Traits\HasRelation;
// هندل کردن روابط دیتابیس مانند تریت لاراول

use \System\Database\Traits\HasAttribute;
// تبدیل رکورد ها و مقادیر دیتابیس به شکل آبجکت و شی مثل : user->age یعنی جدول user و فیلد age جای اینکه بنویسیم $user['age']

// use \System\Database\Traits\HasSoftDelete;
// عملیات سافت دلیت (دلخواه)


abstract class Model
{

     use HasAttribute, HasCRUD, HasMethodCaller, HasQueryBuilder, HasRelation;

     protected $table;
     // نام جدول


     protected $fillable = [];
     // فیلد هایی که میتوانند پر بشوند یا تغیر. مثل name


     protected $hidden = [];
     //فیلد هایی که نیاز به استفاده یا دیده شدن ندارند . مثل پسورد


     protected $casts = [];
     // فیلد هایی که مقدارشون باید سریالایز بشه. مثلا image که چند مقدار دارند


     protected $primaryKey = 'id';
     // کلید اصلی برای استفاده در where و update


     protected $createdAt = 'created_at';
     protected $updatedAt = 'updated_at';

     protected $deletedAt = null;
     // softDelete


     protected $collection = [];
     //  کالکشنی مانند لاراول که مقادیر دیتابیس و جدول رو به صورت آرایه ذخیره میکنه و بعدا این آرایه تبدیل میشه به شی مثل $user->password


}








