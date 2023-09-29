<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPost extends Model
{
    use HasFactory;
    protected $table = 'tbl_post';
    protected $primaryKey = 'post_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'title',
        'review',
        'genre_div'
    ];
}
