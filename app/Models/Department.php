<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    protected $fillable = [
        'department_name',
        'faculty_id',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
}
