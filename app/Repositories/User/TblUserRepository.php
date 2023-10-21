<?php

namespace App\Repositories\User;

use App\Models\TblUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class TblUserRepository
{
    private $tblUser;
    private $tblUserToken;

    
    public function __construct(TblUser $tblUser, TblUser $tblUserToken)
    {
        $this->tblUser = $tblUser;
        $this->tblUserToken = $tblUserToken;
    }


    public function getUserWithChildFirst($user_uuid)
    {
        $search = [
            ['user_uuid', '=', $user_uuid]
        ];

        return $this->tblUser
                ->with(['follows', 'followers'])
                ->where($search)
                ->first();
    }


    public function updateUser($request)
    {
        $search = [
            ['user_uuid', '=', $request->userUuid]
        ];

        if($request->updateIconFlg) {
            $newFilePath = $request->iconPath->store('public/icon');
            $newFilePath = '/storage/icon/' . explode('/', $newFilePath)[2];
            
            $this->tblUser
                ->where($search)
                ->update([
                    'nick_name' => $request->nickName,
                    'pr' => $request->pr,
                    'icon_path' => $newFilePath
                ]);

            return;
        }

        $this->tblUser
            ->where($search)
            ->update([
                'nick_name' => $request->nickName,
                'pr' => $request->pr,
            ]);
    }


    public function findFromMail(string $email): TblUser
    {
        $search = [
            ['email', '=', $email]
        ];

        return $this->tblUser
            ->where($search)
            ->firstOrFail();
    }


    public function updateOrCreateUser(string $userUuid): TblUser
    {
        $now = Carbon::now();
        // $userIdをハッシュ化

        $hashedToken = hash('sha256', $userUuid);
        return $this->tblUserToken->updateOrCreate(
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


    public function getUserTokenFromUser(string $token): TblUser
    {
        $search = [
            ['reset_password_access_key', '=', $token]
        ];

        return $this->tblUserToken
            ->where($search)
            ->firstOrFail();
    }


    public function updateUserPassword(string $password, string $userUuid): void
    {
        $search = [
            ['user_uuid', '=', $userUuid]
        ];

        $this->tblUser
            ->where($search)
            ->update(['password' => $password]);
    }
}