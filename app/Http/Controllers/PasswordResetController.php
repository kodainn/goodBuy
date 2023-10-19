<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetInputMailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Repositories\Interfaces\TblUserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class PasswordResetController extends Controller
{
    private $tblUserRepository;
    private const MAIL_SENDED_SESSION_KEY = 'user_reset_password_mail_sended_action';

    public function __construct(TblUserRepositoryInterface $tblUserRepository)
    {
        $this->tblUserRepository = $tblUserRepository;
    }

    public function index()
    {
        return Inertia::render('PasswordReset/Index');
    }

    public function send(ResetInputMailRequest $request)
    {
        try {
            // ユーザー情報取得
            $user = $this->tblUserRepository->findFromMail($request->email);
            $userToken = $this->tblUserRepository->updateOrCreateUser($user->user_uuid);

            // メール送信
            Mail::to($user->email)->send(new ResetPasswordMail($user, $userToken));
        } catch(\Exception $e) {
            return redirect()->route('password_reset.index');
        }
        // 不正アクセス防止セッションキー
        session()->put(self::MAIL_SENDED_SESSION_KEY, 'user_reset_password_send_email');

        return redirect()->route('password_reset.sendComp');
    }

    public function sendComp()
    {
        if (session()->pull(self::MAIL_SENDED_SESSION_KEY) !== 'user_reset_password_send_email') {
            return redirect()->route('password_reset.index');
            //->with('flash_message', '不正なリクエストです。');
        }
        return Inertia::render('PasswordReset/SendComp');
    }

    public function edit(Request $request)
    {
        // 署名付きURLではない場合
    	if (!$request->hasValidSignature()) {
            return redirect()->route('postlist.index');
        }

        $resetToken = $request->reset_token;

        try {
            // ユーザー情報取得
            $userToken = $this->tblUserRepository->getUserTokenFromUser($resetToken);
        } catch (\Exception $e) {
            return redirect()->route('postlist.index');
                //->with('flash_message', __('パスワード再設定メールに添付されたURLから遷移してください。'));
        }

        return Inertia::render('PasswordReset/Edit', [
            'userToken' => $userToken
        ]);
    }

    public function update(ResetPasswordRequest $request)
    {
        try {
            // ユーザー情報取得
            $userToken = $this->tblUserRepository->getUserTokenFromUser($request->resetToken);
            // パスワード暗号化
            $password = Hash::make($request->password);
            $this->tblUserRepository->updateUserPassword($password, $userToken->user_uuid);
        } catch (\Exception $e) {
            return redirect()->route('password_reset.index');
                //->with('flash_message', __('処理に失敗しました。時間をおいて再度お試しください。'));
        }

        return redirect()->route('password_reset.passwordComp');
    }

    public function passwordComp()
    {
        return Inertia::render('PasswordReset/PasswordComp');
    }
}
