<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectClass extends Pivot
{
    use HasFactory;

    protected $table = 'subject_classes';
    protected $fillable = [
        'classroom_id',
        'subject_id'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function titles()
    {
        return $this->hasMany(Title::class);
    }
}
