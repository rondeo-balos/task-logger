<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model {
    protected $table = 'tags';
    protected $fillable = ['tag','color','workplace_id'];

    public function workplace_id(): Attribute {
        return Attribute::make(
            set: fn ($value) =>  $value ?? session( 'workplace', 1 )
        );
    }
}
