<?php

namespace App\Repositories\Follower;

use App\Models\TblFollower;
use Ramsey\Uuid\Uuid;

class TblFollowerRepository
{

    private $tblFollower;

    public function __construct(TblFollower $tblFollower)
    {
        $this->tblFollower = $tblFollower;
    }


    public function getFollowerWithUserClip($user_uuid, $offset = 1)
    {
        $search = [
            ['user_uuid', '=', $user_uuid]
        ];

        return $this->tblFollower
                ->with('users')
                ->where($search)
                ->offset(($offset - 1) * 50)
                ->limit(50)
                ->get();
    }


    public function getFollowerWithUserLimit($user_uuid, $limit = 1)
    {
        $search = [
            ['user_uuid', '=', $user_uuid]
        ];

        return $this->tblFollower
                ->with('users')
                ->where($search)
                ->offset(0)
                ->limit($limit * 50)
                ->get();
    }


    public function getFollowerCountOfUser($user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid]
        ];

        return $this->tblFollower
                ->where($search)
                ->count();
    }

    public function insertFollower($user_uuid, $follower_user_uuid)
    {
        $this->tblFollower->insert([
            'follower_uuid' => Uuid::uuid4(),
            'user_uuid' => $user_uuid,
            'follower_user_uuid' => $follower_user_uuid
        ]);
    }

    public function deleteFollower($user_uuid, $follower_user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid],
            ['follower_user_uuid', '=', $follower_user_uuid]
        ];

        $this->tblFollower
            ->where($search)
            ->delete();
    }
}