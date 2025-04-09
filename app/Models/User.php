<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'merchant_id',
        'team_id',
        'invited_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }




    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function sentInvites()
    {
        return $this->hasMany(Invite::class, 'created_by');
    }

    public function createdRoles()
    {
        return $this->hasMany(\Spatie\Permission\Models\Role::class, 'created_by');
    }
    
    public function createdPermissions()
    {
        return $this->hasMany(\Spatie\Permission\Models\Permission::class, 'created_by');
    }
}
