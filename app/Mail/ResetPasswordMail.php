<?php

namespace App\Mail;

use App\Models\TblUser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $userToken;

    /**
     * construct
     *
     * @param TblUser $user
     * @param TblUser $userToken
     */
    public function __construct(TblUser $user, TblUser $userToken)
    {
        $this->user = $user;
        $this->userToken = $userToken;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('goodsshare030513@gmail.com', 'goodsShare'),
            subject: 'goodsShareのパスワード設定',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $tokenParam = ['reset_token' => $this->userToken->reset_password_access_key];
        $now = Carbon::now();

        // 署名付き有効期限1時間のURLを生成
        $url = URL::temporarySignedRoute('password_reset.edit' , $now->addHours(1), $tokenParam);
        return new Content(
            view: 'passwordreset.resetmail',
            with: [
                'user' => $this->user,
                'url' => $url
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
