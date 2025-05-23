<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // protected function getPermissionsAttribute()
    // {
    //     return $this->roles->flatMap(function ($role) {
    //         return $role->permissions;
    //     })->unique()->pluck('name');
    // }

    // protected function getRolesAttribute()
    // {
    //     return $this->roles->pluck('name');
    // }

    // protected function getIsAdminAttribute()
    // {
    //     return $this->hasRole('admin');
    // }

    public function store()
    {
        return $this->hasOne(Store::class, 'owner_id');
    }

    // public function cart()
    // {
    //     return $this->hasMany(Cart::class, 'user_id');
    // }

    public function cart()
    {
        return $this->belongsToMany(Product::class, 'cartitems')
                    ->withPivot('quantity') // Attach the quantity column from the pivot table
                    ->withTimestamps(); // Automatically manage created_at/updated_at
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function cartQuantity()
    {
        return $this->cart->count();
    }

    public function cartIsEmpty()
    {
        return $this->cartQuantity === 0;
    }
}
