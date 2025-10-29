<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('メールアドレスの確認')
            ->line('こちらのボタンをクリックして、メールアドレスの認証を完了してください。')
            ->action('メールアドレスを確認する', $this->verificationUrl($notifiable))
            ->line('もしこのメールに心当たりがない場合は、このメッセージを無視してください。');
    }
}