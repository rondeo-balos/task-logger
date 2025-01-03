<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model {
    protected $table = 'tags';
    protected $fillable = ['tag','color','workplace_id'];

    protected static function booted() {
        static::creating( function( $model ) {
            if (session()->has('workplace')) {
                $model->workplace_id = session('workplace');
            }
        });
    }
}
