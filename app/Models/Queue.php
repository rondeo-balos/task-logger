<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model {
    protected $fillable = ['title', 'description', 'tag', 'workplace_id', 'user_id'];
}
