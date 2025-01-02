<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
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

    public function scopeFilter( Builder $query, array $filters ): Builder {
        $query->whereBetween('start', [
            strtotime($filters['range'][0]),
            strtotime($filters['range'][1]),
        ]);

        //$query->ddRawSql();
    
        return $query;
    }
}
