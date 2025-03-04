<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'icon'
    ];

    // إضافة الخاصية التي تُرجع رابط الأيقونة بشكل كامل
    protected $appends = ['icon_url'];

    public function cases(): HasMany
    {
        return $this->hasMany(Thecase::class);
    }

    public function getIconUrlAttribute()
    {
        return $this->icon ? asset('storage/' . $this->icon) : null;
    }
}
