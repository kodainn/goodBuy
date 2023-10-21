<?php

namespace App\Repositories\Follow;

use App\Models\TblFollow;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class TblFollowRepository
{

    private $tblFollow;

    public function __construct(TblFollow $tblFollow)
    {
        $this->tblFollow = $tblFollow;
    }

    public function getFollowAll()
    {
        return $this->tblFollow->all();
    }

    public function getFollowFirst($user_uuid, $follow_user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid],
            ['follow_user_uuid', '=', $follow_user_uuid]
        ];

        return $this->tblFollow
                ->where($search)
                ->first();
    }

    public function getFollowWithUser($user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid]
        ];

        return $this->tblFollow
                ->with('users')
                ->where($search)
                ->get();
    }

    public function getFollowCountOfUser($user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid]
        ];

        return $this->tblFollow
                ->where($search)
                ->count();
    }

    public function insertFollow($user_uuid, $follow_user_uuid)
    {
        $this->tblFollow->insert([
            'follow_uuid' => Uuid::uuid4(),
            'user_uuid' => $user_uuid,
            'follow_user_uuid' => $follow_user_uuid
        ]);
    }

    public function deleteFollow($user_uuid, $follow_user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid],
            ['follow_user_uuid', '=', $follow_user_uuid]
        ];

        $this->tblFollow
            ->where($search)
            ->delete();
    }
}