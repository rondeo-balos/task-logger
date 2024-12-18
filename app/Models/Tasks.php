<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model {
    protected $table = 'tasks';
    protected $fillable = ['title', 'description', 'tag', 'start', 'end', 'workplace_id'];

    protected function description(): Attribute {
        return Attribute::make(
            set: fn (array $value) => json_encode($value) 
        );
    }

    protected function casts(): array {
        return [
            'description' => 'array',
        ];
    }

    public function workplace_id(): Attribute {
        return Attribute::make(
            set: fn () => session( 'workplace', 1 )
        );
    }
}
