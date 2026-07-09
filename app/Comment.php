<?php
namespace App;

use System\Database\ORM\Model;
use System\Database\Traits\HasSoftDelete;

class Comment extends Model{
    use HasSoftDelete;

     protected $table = "comments";
     protected $fillable = ['user_id' , 'post_id' , 'comment' , 'parent_id','status','approved'];


     protected $deletedAt = 'deleted_at';

     public function user(){
        return $this->blongsTo('\App\User' , 'user_id' , 'id');
     }

     public function child(){
            return $this->hasMany('\App\Comment' , 'parent_id' , 'id');
     }

}