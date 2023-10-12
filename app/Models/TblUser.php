<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

class TblUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'tbl_user';
    protected $primaryKey = 'user_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'email',
        'password',
        'nick_name',
        'pr',
        'gender_div',
        'icon_path'
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
    ];

    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function (TblUser $model) {
            empty($model->user_uuid) && $model->user_uuid = Uuid::uuid4();
        });
    }

    public function follows()
    {
        return $this->hasMany(TblFollow::class, 'user_uuid');
    }

    public function followers()
    {
        return $this->hasMany(TblFollower::class, 'user_uuid');
    }

    public function posts()
    {
        return $this->hasMany(TblPost::class, 'user_uuid');
    }
}
