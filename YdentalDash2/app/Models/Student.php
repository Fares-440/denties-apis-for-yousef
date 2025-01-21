<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'city_id',
        'university_id',
        'student_image',
        'name',
        'email',
        'password',
        'confirmPassword',
        'gender',
        'level',
        // 'description',
        'phone_number',
        'university_card_number',
        'university_card_image',
        'userType',
        'isBlocked'
    ];
    // Fields to hide in the JSON response
    protected $hidden = [
        'password',
        'confirmPassword',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    public function review(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function cases(): HasMany
    {
        return $this->hasMany(TheCase::class, 'student_id');
    }
}
