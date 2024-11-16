<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'date',
    ];

    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }
}
