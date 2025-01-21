<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class Patient extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'confirmPassword',
        'id_card',
        'date_of_birth',
        'phone_number',
        'gender',
        'address',
        'userType',
        'isBlocked',
    ];
    // Fields to hide in the JSON response
    protected $hidden = [
        'password',
        'confirmPassword',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    public function review(): HasMany
    {
        return $this->hasMany(Review::class);
    }

}
