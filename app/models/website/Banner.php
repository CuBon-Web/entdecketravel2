<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = "banners";
    protected $fillable = ['id', 'image', 'video', 'status', 'link','title','description','subtitle'];
    
}
