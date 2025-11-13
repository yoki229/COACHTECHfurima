<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    //１　会員登録機能（名前が入力されていない場合、バリデーションメッセージが表示される）
    public function testNameIsRequired()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'hujitani@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    // １　会員登録機能（メールアドレスが入力されていない場合、バリデーションメッセージが表示される）
    public function testEmailIsRequired()
    {
        $response = $this->post('/register', [
            'name' => '藤谷次郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    // １　会員登録機能（パスワードが入力されていない場合、バリデーションメッセージが表示される）
    public function testPasswordIsRequired()
    {
        $response = $this->post('/register', [
            'name' => '藤谷次郎',
            'email' => 'hujitani@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // １　会員登録機能（パスワードが7文字以下の場合、バリデーションメッセージが表示される）
    public function testPasswordMustBeMinimum8Characters()
    {
        $response = $this->post('/register', [
            'name' => '藤谷次郎',
            'email' => 'hujitani@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // １　会員登録機能（パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される）
    public function testPasswordAndConfirmationMustMatch()
    {
        $response = $this->post('/register', [
            'name' => '藤谷次郎',
            'email' => 'hujitani@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password321',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // １　会員登録機能（全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される）
    public function testSuccessfulRegistrationRedirectsToProfile()
    {
        $response = $this->post('/register', [
            'name' => '藤谷次郎',
            'email' => 'hujitani@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録後、プロフィール設定ページにリダイレクトされることを確認
        $response->assertRedirect('/mypage_profile?from=register');
        $this->assertDatabaseHas('users', ['email' => 'hujitani@example.com']);
    }


}
