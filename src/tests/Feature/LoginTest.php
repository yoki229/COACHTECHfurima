<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // メールアドレスが入力されていない場合、バリデーションエラーが表示される
    public function testEmailIsRequired()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    //パスワードが入力されていない場合、バリデーションエラーが表示される
    public function testPasswordIsRequired()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    //入力情報が間違っている場合、バリデーションエラーが表示される
    public function testInvalidCredentialsShowError()
    {
        // ダミーユーザーを作成
        User::factory()->create([
            'email' => 'real@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 間違った情報でログインを試みる
        $response = $this->post('/login', [
            'email' => 'fake@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest(); // ログインしていないことを確認
    }

    //正しい情報が入力された場合、ログインできる
    public function testValidCredentialsLoginSuccessfully()
    {
        // ダミーユーザー作成
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 正しい情報でログイン
        $response = $this->post('/login', [
            'email' => 'loginuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/'); // Fortifyのデフォルトでは / にリダイレクト
        $this->assertAuthenticatedAs($user);
    }

    //ログアウトできる

    public function testLogoutSuccessfully()
    {
        // ログイン済みユーザー作成
        $user = User::factory()->create([
            'email' => 'logoutuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        // ログイン状態にする
        $this->actingAs($user);

        // ログアウトを実行
        $response = $this->post('/logout');

        $response->assertRedirect('/'); // Fortifyデフォルトでトップに戻る
        $this->assertGuest(); // ログアウトしたことを確認
    }
}
