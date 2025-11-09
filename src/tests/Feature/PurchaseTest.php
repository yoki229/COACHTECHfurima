<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // UsersTableSeeder と ItemsTableSeeder を読み込む
    }

    // 商品を購入できる
    public function testUserCanPurchaseItem()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();

        $this->actingAs($user)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'credit_card',
                'address' => $user->address,
            ])
            ->assertRedirect('/mypage_profile?from=purchase');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'buyer_id' => $user->id,
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // 購入した商品は一覧画面で sold と表示
    public function testPurchasedItemShowsSoldInList()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();
        $item->buyer_id = $user->id;
        $item->save();

        $response = $this->actingAs($user)->get('/items');
        $response->assertSee('sold');
    }

    // プロフィール購入履歴に追加される
    public function testPurchasedItemAppearsInProfile()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();
        $item->buyer_id = $user->id;
        $item->save();

        $response = $this->actingAs($user)->get('/mypage_profile');
        $response->assertSee($item->name);
    }

    // 支払い方法変更が反映される
    public function testPaymentMethodCanBeUpdated()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)
            ->post('/purchase/payment', [
                'payment_method' => 'paypal',
            ]);

        $response->assertSessionHas('message', '支払い方法が更新されました');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'payment_method' => 'paypal',
        ]);
    }

    // 送付先住所が商品購入画面に反映される
    public function testAddressReflectedInPurchasePage()
    {
        $user = User::find(1);
        $user->address = '東京都新宿区1-1-1';
        $user->save();

        $response = $this->actingAs($user)->get('/purchase/1');
        $response->assertSee('東京都新宿区1-1-1');
    }

    // 購入商品に送付先住所が紐づく
    public function testPurchasedItemHasShippingAddress()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();

        $this->actingAs($user)
            ->post('/purchase/' . $item->id, [
                'payment_method' => 'credit_card',
                'address' => $user->address,
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address' => $user->address,
        ]);
    }
}
