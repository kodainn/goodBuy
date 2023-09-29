<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblPostImage extends Model
{
    use HasFactory;
    protected $table = 'tbl_post_image';
    protected $primaryKey = 'post_image_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'post_uuid',
        'post_image_path',
        'image_sort'
    ];
}
