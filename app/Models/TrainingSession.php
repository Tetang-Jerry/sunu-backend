<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $table = 'training_sessions';

    protected $fillable = [
        'coach_id',
        'user_id',
        'date_time',
        'duration',
        'status',
        'location',
        'slot_date',
        'slot_period',
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'slot_date' => 'date',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function user()
    {
        return $this->belongsTo(Member::class);
    }

    
}
