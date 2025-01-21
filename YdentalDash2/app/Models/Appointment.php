<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'student_id',
        'thecase_id', // Add thecase_id to fillable
        'status'

    ];
    protected $table = 'appointments';

    const STATUS_UPCOMING = 'بانتظار التأكيد';
    const STATUS_CONFIRMED = 'مؤكد';
    const STATUS_COMPLETED = 'مكتمل';
    const STATUS_CANCELLED = 'ملغي';

    public static function statuses()
    {
        return [
            self::STATUS_UPCOMING,
            self::STATUS_CONFIRMED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }


    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function thecase(): BelongsTo
    {
        return $this->belongsTo(Thecase::class);
    }
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
}
