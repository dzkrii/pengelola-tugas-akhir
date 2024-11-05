<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'nim',
        'gender',
        'department_id',
        'batch_year',
        'phone',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
