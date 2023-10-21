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
        $contact = session()->get('contact');
        if(empty($contact)) {
            $contact = [
                ['email' => ''],
                ['title' => ''],
                ['body' => '']
            ];
        }
        return Inertia::render('Contact/Index', [
            'loginUser' => Auth::user(),
            'contact' => $contact
        ]);
    }

    public function conf(ContactRequest $request)
    {
        session()->put('contact', $request->all());
        return Inertia::render('Contact/Conf', [
            'loginUser' => Auth::user(),
            'inputs' => $request->all()
        ]);
    }

    public function comp(ContactRequest $request)
    {
        Mail::to($request['email'])->send(new ContactSendmail($request, 'user'));
        Mail::to('goodsshare030513@gmail.com')->send(new ContactSendmail($request, 'admin'));

        $request->session()->regenerateToken();

        session()->forget('contact');
        return Inertia::render('Contact/Comp', [
            'loginUser' => Auth::user()
        ]);
    }
}
