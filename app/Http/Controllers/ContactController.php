<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactSendmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $loginUser = Auth::user();
        $contact = session()->get('contact');
        if(empty($contact)) {
            $contact = [
                ['email' => ''],
                ['title' => ''],
                ['body' => '']
            ];
        }
        return Inertia::render('Contact/Index', [
            'loginUser' => $loginUser,
            'contact' => $contact
        ]);
    }

    public function conf(ContactRequest $request)
    {
        $loginUser = Auth::user();
        session()->put('contact', $request->all());
        return Inertia::render('Contact/Conf', [
            'loginUser' => $loginUser,
            'inputs' => $request->all()
        ]);
    }

    public function comp(ContactRequest $request)
    {
        $inputs = $request->except('action');
        //入力されたメールアドレスにメールを送信
        Mail::to($inputs['email'])->send(new ContactSendmail($inputs, 'user'));
        Mail::to('goodsshare030513@gmail.com')->send(new ContactSendmail($inputs, 'admin'));

        //再送信を防ぐためにトークンを再発行
        $request->session()->regenerateToken();

        //送信完了ページのviewを表示
        session()->forget('contact');
        return Inertia::render('Contact/Comp');
    }
}
