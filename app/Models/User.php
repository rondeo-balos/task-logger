<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasFactory, Notifiable, HasRoles;
    
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function workplaces(): HasMany {
        return $this->hasMany(Workplace::class, 'user_id');
    }

    public function sharedWorkplaces() {
        // Get permissions granted to the user that start with 'read'
        $readPermissions = $this->permissions()
            ->where('name', 'LIKE', 'read %')
            ->pluck('name'); // Example: ['read 1', 'read 2']
        //dd( $readPermissions );

        // Extract workplace IDs from permission names
        $workplaceIds = $readPermissions->map( function( $permission ) {
            return intval(str_replace('read ', '', $permission));
        });

        // Fetch workplaces matching the extracted IDs
        return Workplace::whereIn('id', $workplaceIds)->get();
    }

    public function workplace(): Attribute {
        return new Attribute(
            get: fn () => Workplace::find( session('workplace', 1) )
        );
    }
}
