<?php

namespace App\Repositories\Interfaces;

use App\Models\TblUser;

interface TblUserRepositoryInterface
{
    /**
     * メールアドレスからユーザー情報を取得
     *
     * @param string $email
     * @return TblUser
     */
    public function findFromMail(string $email): TblUser;

    /**
     * パスワードリセット用トークンを発行
     *
     * @param string $userUuid
     * @return TblUser
     */
    public function updateOrCreateUser(string $userUuid): TblUser;

    /**
     * トークンからユーザー情報を取得
     * @param string $token
     * @return TblUser
     */
    public function getUserTokenFromUser(string $token): TblUser;

    /**
     * パスワード更新
     *
     * @param string $password
     * @param string $userUuid
     * @return void
     */
    public function updateUserPassword(string $password, string $userUuid): void;
}