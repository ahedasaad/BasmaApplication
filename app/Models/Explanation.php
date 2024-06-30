<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Explanation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title_id',
        'order_explanation_id',
        'title',
        'state',
        'video',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relatedTitle()
    {
        return $this->belongsTo(Title::class,'title_id');
    }

    public function order_explanation()
    {
        return $this->belongsTo(OrderExplanation::class);
    }
}
