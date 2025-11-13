<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    //１０　商品購入機能（「購入する」ボタンを押下すると購入が完了する）
    public function testUserCanPurchaseItem()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();

        $response = $this->actingAs($user)
            ->post('/purchase/' . $item->id,[
                'payment_method' => 'credit_card',
                'address' => $user->address,
            ]);
        
        // Stripe Checkoutページへのリダイレクトを確認
        $this->assertStringContainsString(
            'https://checkout.stripe.com/',
            $response->headers->get('Location')
        );

        $response->assertStatus(302);
    }

    //１０　商品購入機能（購入した商品は商品一覧画面にて「sold」と表示される）
    public function testPurchasedItemShowsSoldInList()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();
        $item->buyer_id = $user->id;
        $item->save();

        $response = $this->actingAs($user)->get('/item/' . $item->id);
        $response->assertSee('sold');
    }

    //１０　商品購入機能（「プロフィール/購入した商品一覧」に追加されている）
    public function testPurchasedItemAppearsInProfile()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();
        $item->buyer_id = $user->id;
        $item->save();

        $response = $this->actingAs($user)->get('/mypage');
        $response->assertSee($item->name);
    }

    //１１　支払い方法選択機能（小計画面で変更が反映される）
    public function testPaymentMethodCanBeUpdated()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();

        Http::fake([
            'https://api.stripe.com/*' => Http::response([
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/test-session'
            ], 200)
        ]);

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'credit_card',
            'address' => $user->address,
        ])
        ->assertRedirect();

        
        // セッションに渡された payment_method を確認
        $response = $this->get('/purchase/'.$item->id);
        $response->assertSee('カード支払い');

        $response = $this->actingAs($user)->post('/purchase/'.$item->id, [
            'payment_method' => 'credit_card',
        ]);

        $response->assertRedirect();
    }

    //１２ 配送先変更機能（送付先住所変更画面にて登録した住所が商品購入画面に反映されている）
    public function testAddressReflectedInPurchasePage()
    {
        $user = User::find(1);
        $user->postal_code = '123-4567';
        $user->address = '東京都新宿区1-1-1';
        $user->building = 'コーポ101';
        $user->save();

        $response = $this->actingAs($user)->get('/purchase/1');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区1-1-1');
        $response->assertSee('コーポ101');
    }

    //１２ 配送先変更機能（購入した商品に送付先住所が紐づいて登録される）
    public function testPurchasedItemHasShippingAddress()
    {
        $user = User::find(1);
        $item = Item::whereNull('buyer_id')->first();

    $response = $this->actingAs($user)
        ->get('/purchase/success/' . $item->id . '?payment_method=credit_card');

    // DBに保存されているか確認
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        'payment_method' => 'カード支払い',
        'postal_code' => $user->postal_code,
        'address' => $user->address,
        'building' => $user->building,
    ]);

    // 商品の購入者が更新されているか
    $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'buyer_id' => $user->id,
    ]);

    $response->assertRedirect('/mypage');
    }
}
