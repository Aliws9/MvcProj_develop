<?php
namespace App;

use System\Database\ORM\Model;

class Setting extends Model{

     protected $table = "setting_meta";
     protected $fillable = ['ket_meta' , 'value_meta' , 'plus'];



}