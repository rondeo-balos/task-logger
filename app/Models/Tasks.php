<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model {
    protected $table = 'tasks';
    protected $fillable = ['title', 'description', 'tag', 'start', 'end', 'workplace_id', 'user_id'];

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

    protected static function booted() {
        static::creating( function( $model ) {
            if (session()->has('workplace')) {
                $model->workplace_id = session('workplace');
            }

            if( auth()->check() ) {
                $model->user_id = \Auth::user()->id;
            }
        });
    }

    public function scopeFilter( Builder $query, array $filters ): Builder {
        $query->whereBetween('start', [
            strtotime($filters['range'][0]),
            strtotime($filters['range'][1]),
        ]);

        if( !empty($filters['user']) )
            $query->where( 'user_id', $filters['user'] );

        //$query->ddRawSql();
    
        return $query;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
