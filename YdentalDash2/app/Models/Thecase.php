<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thecase extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'schedules_id',
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
    public function schedules(): BelongsTo
    {
        // return $this->belongsTo(Schedule::class);
        return $this->belongsTo(Schedule::class, 'schedules_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

}
