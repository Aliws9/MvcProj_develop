<?php

namespace App;

use System\Database\ORM\Model;

class Role extends Model{

     protected $table = "roles";
     protected $fillable = ['name'];
     protected $casts = [];




     // protected $primaryKey = 'id';


     // protected $createdAt = 'created_at';
     // protected $updatedAt = 'updated_at';

     // // protected $deletedAt = null;
     // // // softDelete

     // protected $collection = [];



     public function users(){
          return $this->blongsToMany('\App\Role' , 'user_role' , 'id' , 'role_id' , 'user_id' , 'id');
     }








}