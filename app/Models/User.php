<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function rentalReminders(): HasMany
    {
        return $this->hasMany(RentalReminder::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
