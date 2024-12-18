<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model {
    protected $table = 'notes';
    protected $fillable = ['index', 'content', 'workplace_id'];

    public function workplace_id(): Attribute {
        return Attribute::make(
            set: fn () => session( 'workplace', 1 )
        );
    }
}
