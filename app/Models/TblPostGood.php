<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPostGood extends Model
{
    use HasFactory;
    protected $table = 'tbl_post_good';
    protected $primaryKey = 'post_good_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'post_uuid',
        'user_uuid'
    ];
}
