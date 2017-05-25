<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $fillable = ['barcode','title','publisher','quantity','retail_price','selling_price'];
}

