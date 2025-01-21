<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'visit_date',
        'procedure',
        'note',
        'status',
        'visit_time'
    ];

    protected $table = 'visits';


    const STATUS_UNCOMPLETED = 'غير مكتملة';
    const STATUS_COMPLETED = 'مكتملة';
    const STATUS_CANCELLED = 'ملغية';

    public static function statuses()
    {
        return [
            self::STATUS_UNCOMPLETED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

}
