<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
      protected $fillable = [
    'user_id', 
    'fotos', 
    'videos', 
    'texto',
    'data_img',
    'url_dir',
    'status', 
    ];
 
    protected $table = 'menus';
    public $timestamps = true;
}
