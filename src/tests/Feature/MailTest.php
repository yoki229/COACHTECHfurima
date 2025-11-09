<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class MailTest extends TestCase
{
    use RefreshDatabase; // テストごとにDBをリセット

    //会員登録後、認証メールが送信される
    public function testRegistrationSendsVerificationEmail()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'yamada@example.com')->first();

        $this->assertDatabaseHas('users', ['email' => 'yamada@example.com']);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    //メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
    public function testEmailCheckRedirectsToVerificationPageIfNotVerified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/email/check');

        $response->assertRedirect('/email');
    }


    //メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する
    public function testEmailVerificationCompletesAndRedirects()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        // 通知を明示的に送る
        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class);

        // EmailVerificationRequestをシミュレート
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect('/mypage_profile?from=register');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
