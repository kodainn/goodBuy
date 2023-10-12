<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblFollower extends Model
{
    use HasFactory;
    protected $table = 'tbl_follower';
    protected $primaryKey = 'follower_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'follower_user_uuid'
    ];

    public function users()
    {
        return $this->hasMany(TblUser::class, 'user_uuid', 'follower_user_uuid');
    }
}
