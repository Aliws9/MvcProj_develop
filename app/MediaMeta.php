<?php

namespace App;

use System\Database\ORM\Model;


class MediaMeta extends Model{


     protected $table = "media_meta";

     protected $fillable = ['media_id' , 'key' , 'value'];

}