<?php

namespace App;

use System\Database\ORM\Model;

class Post extends Model{

     protected $table = "posts";

     protected $fillable = ['title' , 'body' , 'cat_id'];

     protected $casts = [];

     // protected $primaryKey = 'id';


     // protected $createdAt = 'created_at';
     // protected $updatedAt = 'updated_at';

     // // protected $deletedAt = null;
     // // // softDelete

     // protected $collection = [];






     public function Category(){
          return $this->blongsTo('\App\Category' , 'cat_id' , 'id');
     }









}