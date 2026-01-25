<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tasks;
use App\Models\Workplace;

class Board extends Model {
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'attachments',
        'workplace_id',
    ];

    protected function casts(): array {
        return [
            'description' => 'array',
            'attachments' => 'array',
            'due_date' => 'datetime',
        ];
    }

    protected static function booted() {
        static::creating(function ($model) {
            if (session()->has('workplace')) {
                $model->workplace_id = session('workplace');
            }

            if (! $model->status) {
                $model->status = 'pending';
            }
        });
    }

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function tasks() {
        return $this->hasMany(Tasks::class, 'board_id');
    }

    public function workplace() {
        return $this->belongsTo(Workplace::class);
    }
}
