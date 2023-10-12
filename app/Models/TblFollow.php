<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblFollow extends Model
{
    use HasFactory;
    protected $table = 'tbl_follow';
    protected $primaryKey = 'follow_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'follow_user_uuid'
    ];

    public function users()
    {
        return $this->hasMany(TblUser::class, 'user_uuid', 'follow_user_uuid');
    }
}
