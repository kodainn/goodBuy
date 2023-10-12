<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSendmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $name;
    private $title;
    private $body;
    private $form;
    public $subject;
    public $view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs, $role)
    {
        $this->email = $inputs['email'];
        $this->title = $inputs['title'];
        $this->body  = $inputs['body'];

        if($role === 'admin') {
            $this->form['email'] = $inputs['email'];
            $this->form['name'] = 'goodsShare会員';
            $this->subject = '会員からのお問い合わせ';
            $this->view = 'contact.adminmail';
        } else {
            $this->form['email'] = 'goodsshare030513@gmail.com';
            $this->form['name'] = 'goodsShare';
            $this->subject = 'お問い合わせ送信完了のお知らせ';
            $this->view = 'contact.usermail';
        }
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->form['email'], $this->form['name']),
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: $this->view,
            with: [
                'email' => $this->email,
                'title' => $this->title,
                'body'  => $this->body
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
