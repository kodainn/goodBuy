<?php

namespace App\Repositories\Eloquents;

use App\Models\TblUser;
use App\Repositories\Interfaces\TblUserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class TblUserRepository implements TblUserRepositoryInterface
{
    private $user;
    private $userToken;

    /**
     * constructor
     *
     * @param TblUser $user
     */
    public function __construct(TblUser $user, TblUser $userToken)
    {
        $this->user = $user;
        $this->userToken = $userToken;
    }

    // メールアドレスからユーザー情報取得
    public function findFromMail(string $email): TblUser
    {
        return $this->user->where('email', $email)->firstOrFail();
    }

    // パスワードリセット用トークンを発行
    public function updateOrCreateUser(string $userUuid): TblUser
    {
        $now = Carbon::now();
        // $userIdをハッシュ化

        $hashedToken = hash('sha256', $userUuid);
        return $this->userToken->updateOrCreate(
            [
                'user_uuid' => $userUuid,
            ],
            [
            // $hashedTokenを含むトークンを作成
            'reset_password_access_key' => uniqid(rand(), $hashedToken),
            // トークンの有効期限を現在から1時間後に設定
            'reset_password_expire_at' => $now->addHours(1)->toDateTimeString()
            ]);
    }

    // トークンからユーザー情報を取得
    public function getUserTokenFromUser(string $token): TblUser
    {
        return $this->userToken->where('reset_password_access_key', $token)->firstOrFail();
    }

    // パスワード更新
    public function updateUserPassword(string $password, string $userUuid): void
    {
        $this->user->where('user_uuid', $userUuid)->update(['password' => $password]);
    }
}