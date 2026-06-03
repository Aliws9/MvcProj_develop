<?php

namespace App;

use System\Database\ORM\Model;

use System\Database\Traits\HasSoftDelete;

class Post extends Model{

    use HasSoftDelete;

    protected $deletedAt = 'deleted_at';

     protected $table = "posts";

     protected $fillable = ['title' , 'body' , 'image' , 'user_id' , 'cat_id' , 'status' , 'published_at'];

     protected $casts = ['image' => 'array'];

     public function category(){
          return $this->blongsTo('\App\Category' , 'cat_id' , 'id');
     }
     public function user(){
          return $this->blongsTo('\App\User' , 'user_id' , 'id');
     }

     public function postMeta(){
        return $this->hasMany('\App\PostMeta' , 'post_id' , 'id');
     }

}