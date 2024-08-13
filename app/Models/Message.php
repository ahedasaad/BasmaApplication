<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * تحديد الأعمدة القابلة للملء.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    /**
     * العلاقة مع نموذج المستخدم (المرسل).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * العلاقة مع نموذج المستخدم (المستقبل).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
