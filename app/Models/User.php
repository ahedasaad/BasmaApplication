<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'user_name',
        'account_type',
        'address',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function order_explanations()
    {
        return $this->hasMany(OrderExplanation::class);
    }

    public function explanations()
    {
        return $this->hasMany(Explanation::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function buy_products()
    {
        return $this->hasMany(BuyProduct::class);
    }

    public function representative_products()
    {
        return $this->hasMany(BuyProduct::class, 'representative_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    public function child_profile()
    {
        return $this->hasOne(ChildProfile::class);
    }

    public function accountTypes()
    {
        return $this->belongsToMany(AccountType::class, 'user_types');
    }

    public function basket()
    {
        return $this->hasOne(Basket::class);
    }
}
