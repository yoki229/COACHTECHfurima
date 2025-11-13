<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class MailTest extends TestCase
{
    use RefreshDatabase;

    //１６　メール認証機能（会員登録後、認証メールが送信される）
    public function testRegistrationSendsVerificationEmail()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => '藤谷次郎',
            'email' => 'hujitani@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'hujitani@example.com')->first();
        $this->assertDatabaseHas('users', ['email' => 'hujitani@example.com']);
        Notification::assertSentTo($user, CustomVerifyEmail::class);
    }

    //１６　メール認証機能（メール未認証の場合、メール認証導線画面を再表示する（コーチ改））
    public function testEmailCheckRedirectsToVerificationPageIfNotVerified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $response = $this->get('/email/check');
        $response->assertRedirect('/email');
    }


    //１６　メール認証機能（メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する）
    public function testEmailVerificationCompletesAndRedirects()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        // 通知を明示的に送る
        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, CustomVerifyEmail::class);

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
