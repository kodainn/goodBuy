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

    public function images()
    {
        return $this->hasMany(TblPostImage::class, 'post_uuid');
    }

    public function goods()
    {
        return $this->hasMany(TblPostGood::class, 'post_uuid');
    }

    public function user()
    {
        return $this->belongsTo(TblUser::class, 'user_uuid');
    }
}
