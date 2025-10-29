<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        // Bladeの内容をHTMLに変換
        $html = view('emails.test')->render();

        // HTMLメールとして送信
        Mail::html($html, function ($message) {
            $message->to('abc@example.com', 'test')
                    ->subject('This is a test mail');
        });

        return 'HTMLメールを送信しました ✨';
    }
}
