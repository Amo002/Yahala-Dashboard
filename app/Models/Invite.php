<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'expires_at',
        'created_by',
    ];

    protected $dates = ['expires_at'];

    /**
     * User who sent the invite.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: not expired invites
     */
    public function scopeActive($query)
    {
        return $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
    }
}
