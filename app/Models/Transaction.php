<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $fillable = ['transaction_type_id','book_id','quantity','profit','created_at','updated_at'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}

