<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPostMessage extends Model
{
    use HasFactory;
    protected $table = 'tbl_post_message';
    protected $primaryKey = 'post_message_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'post_uuid',
        'user_uuid',
        'message'
    ];
}
