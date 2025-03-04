<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

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

    protected $appends = ['student_image_url', 'university_card_image_url'];


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

    public function setPasswordAttribute($value)
    {
        // Only hash if the value is not already hashed
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
    public function getStudentImageUrlAttribute()
    {
        return $this->student_image ? asset('storage/' . $this->student_image) : null;
    }

    public function getUniversityCardImageUrlAttribute()
    {
        return $this->university_card_image ? asset('storage/' . $this->university_card_image) : null;
    }
}
