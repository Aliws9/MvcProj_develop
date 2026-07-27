<?php

namespace App;

use System\Database\ORM\Model;

use System\Database\Traits\HasSoftDelete;


class User extends Model
    {
    use HasSoftDelete;
    protected $deletedAt = 'deleted_at';

    protected $table = "users";

    protected $fillable = ['username', 'email', 'password', 'first_name', 'last_name', 'is_active', 'avatar', 'bio', 'user_type', 'verify_token', 'status', 'remember_token', 'remember_token_expire'];

    // protected $casts = [];

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