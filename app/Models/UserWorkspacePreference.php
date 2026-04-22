<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWorkspacePreference extends Model {
    protected $fillable = ['user_id', 'shared_workspace_id', 'personal_workspace_id'];
}
