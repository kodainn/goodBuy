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

    public function getFollowerWithUser($user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid]
        ];

        return $this->tblFollower
                ->with('users')
                ->where($search)
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