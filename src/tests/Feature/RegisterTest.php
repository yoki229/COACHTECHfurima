<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase; // テストごとにDBをリセット

    //名前が入力されていない場合、バリデーションメッセージが表示される
    public function testNameIsRequired()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'yamada@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    public function testEmailIsRequired()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される
    public function testPasswordIsRequired()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // パスワードが7文字以下の場合、バリデーションメッセージが表示される
    public function testPasswordMustBeMinimum8Characters()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    public function testPasswordAndConfirmationMustMatch()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password321',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される
    public function testSuccessfulRegistrationRedirectsToProfile()
    {
        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録後、プロフィール設定ページにリダイレクトされることを確認
        $response->assertRedirect('/mypage_profile?from=register');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }


}
