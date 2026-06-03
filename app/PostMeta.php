<?php

namespace App;

use System\Database\ORM\Model;


class PostMeta extends Model{


     protected $table = "posts_meta";

     protected $fillable = ['post_id' , 'key' , 'value'];

}