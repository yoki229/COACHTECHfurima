<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // プロフィールに必要情報が表示される
    public function testProfileDisplaysUserAndItems()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/mypage_profile');

        $response->assertSee($user->name);
        $response->assertSee($user->profile_image);
        $response->assertSee('購入済み商品');
        $response->assertSee('出品商品');
    }

    // プロフィールの初期値が反映される
    public function testProfileShowsInitialValues()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/mypage_profile');

        $response->assertSee($user->name);
        $response->assertSee($user->profile_image);
        $response->assertSee($user->postal_code);
        $response->assertSee($user->address);
    }

    // いいねを押下して登録できる
    public function testUserCanLikeItem()
    {
        $user = User::find(1);
        $item = Item::first();

        $this->actingAs($user)
            ->post('/item/' . $item->id . '/like')
            ->assertStatus(200);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // いいね済みアイコンは色が変化する（ビュー上での表示確認）
    public function testLikedItemShowsActiveIcon()
    {
        $user = User::find(1);
        $item = Item::first();

        // いいね
        $this->actingAs($user)
            ->post('/item/' . $item->id . '/like');

        $response = $this->get('/items/' . $item->id);
        $response->assertSee('liked');
    }

    // 再度いいねを押すと解除される
    public function testUserCanUnlikeItem()
    {
        $user = User::find(1);
        $item = Item::first();

        $this->actingAs($user)
            ->post('/item/' . $item->id . '/like');
        $this->post('/item/' . $item->id . '/like');

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // ログイン済みユーザーはコメントを送信できる
    public function testLoggedInUserCanComment()
    {
        $user = User::find(1);
        $item = Item::first();

        $this->actingAs($user)
            ->post('/item/' . $item->id . '/comment', [
                'comment' => 'テストコメント'
            ])
            ->assertStatus(302); // リダイレクトで成功

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }

    // ログイン前ユーザーはコメントを送信できない
    public function testGuestCannotComment()
    {
        $item = Item::first();

        $response = $this->post('/item/' . $item->id . '/comment', [
            'comment' => 'ゲストコメント'
        ]);

        $response->assertRedirect('/login'); // ゲストはログイン画面にリダイレクト
    }

    // コメントが入力されていない場合はバリデーションエラー
    public function testCommentRequiredValidation()
    {
        $user = User::find(1);
        $item = Item::first();

        $response = $this->actingAs($user)
            ->post('/item/' . $item->id . '/comment', [
                'comment' => ''
            ]);

        $response->assertSessionHasErrors('comment');
    }

    // コメントが255文字以上の場合はバリデーションエラー
    public function testCommentMaxLengthValidation()
    {
        $user = User::find(1);
        $item = Item::first();

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->post('/item/' . $item->id . '/comment', [
                'comment' => $longComment
            ]);

        $response->assertSessionHasErrors('comment');
    }

}
