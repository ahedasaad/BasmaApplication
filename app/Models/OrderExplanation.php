<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderExplanation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_class_id',
        'title_id',
        'note',
        'state',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject_class()
    {
        return $this->belongsTo(SubjectClass::class);
    }

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function explanations()
    {
        return $this->hasMany(Explanation::class);
    }
}
