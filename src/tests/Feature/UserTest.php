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

    // 8　いいね機能（いいねアイコンを押下することによって、いいねした商品として登録することができる。）
    public function testUserCanLikeItem()
    {
        $user = User::find(1);
        $item = Item::find(3);

        $this->actingAs($user)
            ->post('/item/' . $item->id . '/like')
            ->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // 8　いいね機能（追加済みのアイコンは色が変化する）
    public function testLikedItemShowsActiveIcon()
    {
        $user = User::find(1);
        $item = Item::first();

        // いいね
        $this->actingAs($user)
            ->post('/item/' . $item->id . '/like');

        $response = $this->get('/item/' . $item->id);
        $response->assertSee('liked');
    }

    // 8　いいね機能（再度いいねアイコンを押下することによって、いいねを解除することができる。）
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

    // 9　コメント送信機能（ログイン済みユーザーはコメントを送信できる）
    public function testLoggedInUserCanComment()
    {
        $user = User::find(1);
        $item = Item::first();

        $this->actingAs($user)
            ->post('/item/' . $item->id . '/comments_store', [
                'comment' => 'テストコメント'
            ])
            ->assertStatus(302); // リダイレクトで成功

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }

    // 9　コメント送信機能（ログイン前のユーザーはコメントを送信できない）
    public function testGuestCannotComment()
    {
        $item = Item::first();

        $response = $this->post('/item/' . $item->id . '/comments_store', [
            'comment' => 'ゲストコメント'
        ]);

        $response->assertRedirect('/login'); // ゲストはログイン画面にリダイレクト
    }

    // 9　コメント送信機能（コメントが入力されていない場合、バリデーションメッセージが表示される）
    public function testCommentRequiredValidation()
    {
        $user = User::find(1);
        $item = Item::first();

        $response = $this->actingAs($user)
            ->post('/item/' . $item->id . '/comments_store', [
                'comment' => ''
            ]);

        $response->assertSessionHasErrors('comment');
    }

    // 9　コメント送信機能（コメントが255字以上の場合、バリデーションメッセージが表示される）
    public function testCommentMaxLengthValidation()
    {
        $user = User::find(1);
        $item = Item::first();

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->post('/item/' . $item->id . '/comments_store', [
                'comment' => $longComment
            ]);

        $response->assertSessionHasErrors('comment');
    }

    // 13 ユーザー情報取得（必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧））
    public function testProfileDisplaysUserAndItems()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/mypage');
        $response->assertStatus(200);

        $response->assertSee($user->name);
        $response->assertSee($user->profile_image);
        $response->assertSee('購入した商品');
        $response->assertSee('出品した商品');
    }

    // 14　ユーザー情報変更（変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所））
    public function testProfileShowsInitialValues()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->get('/mypage_profile');

        $response->assertSee($user->name);
        $response->assertSee($user->profile_image);
        $response->assertSee($user->postal_code);
        $response->assertSee($user->address);
    }

}
