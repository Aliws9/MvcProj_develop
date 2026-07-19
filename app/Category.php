<?php
namespace App;

use System\Database\ORM\Model;
use System\Database\Traits\HasSoftDelete;

class Category extends Model{
    use HasSoftDelete;

     protected $table = "categories";
     protected $fillable = ['name' , 'parent_id' , 'description'];


     protected $deletedAt = 'deleted_at';
    //  protected $casts = [];

    public function posts(){
        return $this->hasMany('\App\Post' , 'cat_id' , 'id');
    }

}