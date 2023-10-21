<?php

namespace App\Repositories\Post;

use App\Models\TblPostGood;
use Ramsey\Uuid\Uuid;

class TblPostGoodRepository
{
    private $tblPostGood;

    public function __construct(TblPostGood $tblPostGood)
    {
        $this->tblPostGood = $tblPostGood;
    }

    public function getSearchPostGoodFirst($post_uuid, $user_uuid)
    {
        $search = [
            ['post_uuid', '=', $post_uuid],
            ['user_uuid', '=', $user_uuid]
        ];
        return $this->tblPostGood
                ->where($search)
                ->first();
    }

    public function insertPostGood($post_uuid, $user_uuid)
    {
        $this->tblPostGood->insert([
            'post_good_uuid' => Uuid::uuid4(),
            'post_uuid' => $post_uuid,
            'user_uuid' => $user_uuid
        ]);
    }

    public function deletePostGood($post_uuid, $user_uuid)
    {
        $search = [
            ['post_uuid', '=', $post_uuid],
            ['user_uuid', '=', $user_uuid]
        ];
        $this->tblPostGood
            ->where($search)
            ->delete();
    }
}