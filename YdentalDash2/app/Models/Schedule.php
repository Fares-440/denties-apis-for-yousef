<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'available_date',
        'available_time',
        'thecase_id'
    ];
    public function cases(): BelongsTo
    {
        return $this->belongsTo(Thecase::class);
    }

}
