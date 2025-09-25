<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'availability_json',
        'bio',
    ];

    protected $casts = [
        // availability_json is an array of: [{date: 'YYYY-MM-DD', periods: ['09:00-12:00','14:00-18:00']}]
        'availability_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
