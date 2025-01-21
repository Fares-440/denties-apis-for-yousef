<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'student_id',
        'complaint_type',
        'complaint_title',
        'complaint_desciption',
        'complaint_date'

    ];
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

}
