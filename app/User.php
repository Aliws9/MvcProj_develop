<?php

namespace App;

use System\Database\ORM\Model;

class User extends Model{

     protected $table = "users";

     protected $fillable = ['username'];

     protected $casts = [];

     // protected $primaryKey = 'id';


     // protected $createdAt = 'created_at';
     // protected $updatedAt = 'updated_at';

     // // protected $deletedAt = null;
     // // // softDelete

     // protected $collection = [];

    //  public function roles(){
    //       return $this->blongsToMany('\App\User' , 'user_role' , 'id' , 'user_id' , 'role_id' , 'id');
    //  }



}