<?php

namespace App;

use System\Database\ORM\Model;


class Media extends Model{

    protected $deletedAt = 'deleted_at';

     protected $table = "media";

     protected $fillable = ['file_name' , 'path' , 'url' , 'format' , 'mime_type' , 'size' , 'width' , 'height' , 'user_id' , ''];

     protected $casts = ['image' => 'array'];

     public function media_meta(){
          return $this->blongsTo('\App\Category' , 'cat_id' , 'id');
     }

}