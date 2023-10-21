<?php

namespace App\Repositories\TypeDiv;

use App\Models\TblTypeDiv;

class TblTypeDivRepository
{

    private $tblTypeDiv;

    public function __construct(TblTypeDiv $tblTypeDiv)
    {
        $this->tblTypeDiv = $tblTypeDiv;
    }

    public function getGenreKvAll()
    {
        $typeDiv = $this->tblTypeDiv->where('type_name', '=', 'genre')->get();
        $typeDivKv = [];
        foreach($typeDiv as $col) {
            $typeDivKv[$col['type_detail_div']] = $col['type_detail_name'];
        }
        return $typeDivKv;
    }
}