<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birthdate',
        'date_of_join',
        'date_of_exit',
        'starting_disease',
        'healing_date',
        'disease_type',
        'note',
        'image',
    ];

    protected $dates = [
        'birthdate',
        'date_of_join',
        'date_of_exit',
        'starting_disease',
        'healing_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
