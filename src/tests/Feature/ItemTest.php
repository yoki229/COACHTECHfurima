<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // 全商品を取得できる
    public function testAllItemsAreDisplayed()
    {
        $response = $this->get('/items');
        $response->assertStatus(200);

        foreach (Item::all() as $item) {
            $response->assertSee($item->name);
        }
    }

    // 購入済み商品はSoldと表示される
    public function testSoldItemsShowSoldLabel()
    {
        $item = Item::whereNotNull('buyer_id')->first();

        $response = $this->get('/items');
        $response->assertSee('Sold');
        $response->assertSee($item->name);
    }

    // 自分が出品した商品は表示されない
    public function testUserDoesNotSeeOwnItems()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->get('/items');

        $ownItems = Item::where('user_id', $user->id)->get();
        $otherItems = Item::where('user_id', '!=', $user->id)->get();

        foreach ($ownItems as $item) {
            $response->assertDontSee($item->name);
        }
        foreach ($otherItems as $item) {
            $response->assertSee($item->name);
        }
    }

    // いいねした商品だけがマイリストに表示される
    public function testOnlyLikedItemsAreShownInMyList()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $likedItem = Item::first();
        Like::insert(['user_id' => $user->id, 'item_id' => $likedItem->id]);

        $response = $this->get('/mylist');
        $response->assertSee($likedItem->name);
    }

    // 購入済み商品はSoldと表示される(マイリスト)
    public function testSoldItemsMyListShowSoldLabel()
    {
        $item = Item::whereNotNull('buyer_id')->first();

        $response = $this->get('/mylist');
        $response->assertSee('Sold');
        $response->assertSee($item->name);
    }

    // 未認証の場合はマイリストに何も表示されない
    public function testMyListIsEmptyForGuest()
    {
        $response = $this->get('/mylist');
        foreach (Item::all() as $item) {
            $response->assertDontSee($item->name);
        }
    }

    // 商品名で部分一致検索ができる
    public function testSearchByItemName()
    {
        $keyword = '腕時計';
        $response = $this->get('/items?search=' . $keyword);
        $response->assertSee($keyword);
    }

    // 検索状態がマイリストでも保持されている
    public function testSearchStateIsPreservedInMyList()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $keyword = '腕時計';
        $this->get('/items?search=' . $keyword);

        $response = $this->get('/mylist?search=' . $keyword);
        $response->assertSee($keyword);
    }

    // 必要な情報が商品詳細ページに表示される
    public function testItemDetailDisplaysAllInfo()
    {
        $item = Item::first();
        $response = $this->get('/items/' . $item->id);

        $response->assertSee($item->name)
                ->assertSee($item->description)
                ->assertSee($item->brand ?? '')
                ->assertSee($item->price)
                ->assertSee($item->item_image);
    }

    // 複数カテゴリが表示されるか
    public function testMultipleCategoriesAreDisplayed()
    {
        $item = Item::first();
        $response = $this->get('/items/' . $item->id);

        foreach ($item->categories as $category) {
            $response->assertSee($category->name);
        }
    }

    // 商品出品画面に必要な情報が保存できる
    public function testUserCanCreateItem()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->post('/items', [
                'name' => '新商品',
                'price' => 1000,
                'brand' => 'ブランドA',
                'description' => '商品の説明',
                'status_id' => 1,
                'category' => [1, 3],
                'item_image' => 'sample.jpg',
            ]);

        $response->assertRedirect('/items');
        $this->assertDatabaseHas('items', [
            'name' => '新商品',
            'user_id' => $user->id,
        ]);
    }
}
