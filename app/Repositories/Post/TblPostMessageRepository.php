<?php

namespace App\Repositories\Post;

use App\Models\TblPostMessage;
use Ramsey\Uuid\Uuid;

class TblPostMessageRepository
{
    private $tblPostMessage;

    public function __construct(TblPostMessage $tblPostMessage)
    {
        $this->tblPostMessage = $tblPostMessage;
    }

    public function getPostMessageWithUser($post_uuid)
    {
        $search = [
            ['post_uuid', '=', $post_uuid]
        ];
        return $this->tblPostMessage
                ->where($search)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
    }

    public function getPostMessageCount($post_uuid)
    {
        $search = [
            ['post_uuid', '=', $post_uuid]
        ];
        return $this->tblPostMessage
                ->where($search)
                ->count();
    }

    public function insertPostMessage($post_uuid, $user_uuid, $message)
    {
        $this->tblPostMessage->insert([
            [
                'post_message_uuid' => Uuid::uuid4(),
                'post_uuid' => $post_uuid,
                'user_uuid' => $user_uuid,
                'message' => $message
            ]
        ]);
    }
}