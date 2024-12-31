<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workplace extends Model {
    protected $fillable = ['name'];

    protected static function booted() {
        static::creating(function ($model) {
            // Attaching user to the newly created workplace
            if (Auth::check() && is_null($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });
    }

    public function tasks(): HasMany {
        return $this->hasMany(Tasks::class, 'workplace_id')
            //->whereBetween('start', [strtotime(date('Y-m-01')), strtotime(date('Y-m-t'))])
            ->orderByDesc( 'start' );
    }

    public function tags(): HasMany {
        return $this->hasMany(Tags::class, 'workplace_id');
    }

    public function notes(): HasMany {
        return $this->hasMany(Notes::class, 'workplace_id');
    }

    protected $appends = ['current'];
    
    public function current(): Attribute {
        return Attribute::make(
            get: fn () => $this->id == session('workplace', 1)
        );
    }
}
