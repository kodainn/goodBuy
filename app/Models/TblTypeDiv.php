<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblTypeDiv extends Model
{
    use HasFactory;
    protected $table = 'tbl_type_div';
    protected $primaryKey = 'type_id';
}
