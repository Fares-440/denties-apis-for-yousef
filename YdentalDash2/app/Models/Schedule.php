<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'available_date',
        'available_time'
    ];
    public function cases(): HasMany
    {
        return $this->hasMany(Thecase::class);
    }

}
