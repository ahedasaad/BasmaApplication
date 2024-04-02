<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_class_id',
        'name'
    ];

    public function subject_class()
    {
        return $this->belongsTo(SubjectClass::class);
    }

    public function order_explanations()
    {
        return $this->hasMany(OrderExplanation::class);
    }

    public function explanations()
    {
        return $this->hasMany(Explanation::class);
    }
}
