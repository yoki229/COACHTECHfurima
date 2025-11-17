<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    //４　商品一覧取得（全商品を取得できる）
    public function testAllItemsAreDisplayed()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        foreach (Item::all() as $item) {
            $response->assertSee($item->name);
        }
    }

    //４　商品一覧取得（購入済み商品はSoldと表示される）
    public function testSoldItemsShowSoldLabel()
    {
        $item = Item::whereNotNull('buyer_id')->first();

        $response = $this->get('/');
        $response->assertSee('sold');
        $response->assertSee($item->name);
    }

    //４　商品一覧取得（自分が出品した商品は表示されない）
    public function testUserDoesNotSeeOwnItems()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get('/');

        $ownItems = Item::where('user_id', $user->id)->get();
        $otherItems = Item::where('user_id', '!=', $user->id)->get();

        foreach ($ownItems as $item) {
            $response->assertDontSee($item->name);
        }
        foreach ($otherItems as $item) {
            $response->assertSee($item->name);
        }
    }

    //５　マイリスト一覧取得（いいねした商品だけがマイリストに表示される）
    public function testOnlyLikedItemsAreShownInMyList()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $likedItem = Item::first();
        Like::insert(['user_id' => $user->id, 'item_id' => $likedItem->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist');
        $response->assertSee($likedItem->name);
    }

    //５　マイリスト一覧取得（購入済み商品はSoldと表示される(マイリスト)）
    public function testSoldItemsMyListShowSoldLabel()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $item = Item::whereNotNull('buyer_id')->first();

        Like::firstOrCreate([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist');
        $response->assertSee('sold');
        $response->assertSee($item->name);
    }

    //５　マイリスト一覧取得（未認証の場合はマイリストに何も表示されない）
    public function testMyListIsEmptyForGuest()
    {
        $response = $this->get('/?tab=mylist');
        foreach (Item::all() as $item) {
            $response->assertDontSee($item->name);
        }
    }

    //６　商品検索機能（「商品名」で部分一致検索ができる）
    public function testSearchByItemName()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $item = Item::first();

        $keyword = '腕時計';
        $response = $this->get('/search?keyword=' . $keyword);
        $response->assertSee($keyword);
    }

    //６　商品検索機能（検索状態がマイリストでも保持されている）
    public function testSearchStateIsPreservedInMyList()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $item = Item::first();

        $keyword = '腕時計';
        $response = $this->actingAs($user)->get('/search/?tab=mylist&keyword=' . $keyword);
        $response->assertSee($keyword);
    }

    //７　商品詳細情報取得（必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容））
    public function testItemDetailDisplaysAllInfo()
    {
        $item = Item::first();
        $likeCount = $item->likedUsers()->count();
        $commentCount = $item->comments()->count();
        $category = $item->categories()->first();

        // テスト用のコメントを作成
        $comment = Comment::create([
            'item_id' => $item->id,
            'user_id' => User::first()->id,
            'comment' => 'テスト用コメント',
        ]); 

        $response = $this->get('/item/' . $item->id);

        $response->assertSee(asset($item->item_image))
                ->assertSee($item->name)
                ->assertSee($item->brand ?? '')
                ->assertSee($item->price)
                ->assertSee($likeCount)
                ->assertSee($commentCount)
                ->assertSee($item->description)
                ->assertSee($category->name)
                ->assertSee($item->status->name)
                ->assertSee($commentCount)
                ->assertSee($comment->user->name)
                ->assertSee($comment->comment);
    }

    //７　商品詳細情報取得（複数選択されたカテゴリが表示されているか）
    public function testMultipleCategoriesAreDisplayed()
    {
        $item = Item::first();
        $response = $this->get('/item/' . $item->id);

        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }

    //１５　出品商品情報登録（商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格））
    public function testUserCanCreateItem()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $file = new UploadedFile(
            storage_path('app/public/item_images/bag.jpg'),
            'test.png',
            'image/png',
            null,
            true
        );

        $response = $this->post('/sell', [
                'item_image' => $file,
                'category' => [1, 3],
                'status' => 1,
                'name' => '新商品',
                'brand' => 'ブランドA',
                'description' => '商品の説明',
                'price' => 1000,
            ]);

        $response->assertRedirect('/mypage');
        
        $this->assertDatabaseHas('items', [
            'name' => '新商品',
            'user_id' => $user->id,
        ]);
    }
}
