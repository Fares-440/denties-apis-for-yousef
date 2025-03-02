<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thecase extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'procedure',
        'gender',
        'description',
        'cost',
        'min_age',
        'max_age',
        'student_id', // Add student_id to fillable

    ];
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'thecase_id');
    }


}
