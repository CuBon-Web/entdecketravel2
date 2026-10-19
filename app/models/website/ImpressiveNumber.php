<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class ImpressiveNumber extends Model
{
    protected $table = 'impressive_numbers';
    protected $fillable = ['name', 'value', 'suffix', 'image', 'status', 'sort_order'];
}
