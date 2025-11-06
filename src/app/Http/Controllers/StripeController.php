<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class StripeController extends Controller
{

    // 商品購入画面の表示
    public function purchase($item_id){
        $user = auth()->user();
        $item = Item::findOrFail($item_id);
        $payments = Order::PAYMENT_METHODS;

        return view('purchase', compact('user', 'item', 'payments'));
    }

    // Stripe決済処理
    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // Stripe初期化
        Stripe::setApiKey(config('services.stripe.secret'));

        // 支払い方法（カード or コンビニ）
        $paymentMethod = $request->payment_method;

        // Stripe Checkoutセッションを作成
        $sessionData = [
            'payment_method_types' =>
                $paymentMethod === 'convenience'
                    ? ['konbini']   // コンビニ払い
                    : ['card'],     // カード払い

            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                        'description' => "支払い方法：" . Order::PAYMENT_METHODS[$paymentMethod],
                    ],
                    'unit_amount' => $item->price, // 円単位
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            'success_url' => url("/purchase/success/{$item->id}?payment_method={$paymentMethod}"),
            'cancel_url' => url('/purchase/cancel'),
        ];

        $checkoutSession = CheckoutSession::create($sessionData);

        // Stripeの外部決済ページにリダイレクト
        return redirect($checkoutSession->url);
    }

    // 決済成功時に呼ばれる
    public function success(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // DBに購入情報を保存
        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => Order::PAYMENT_METHODS[$request->payment_method],
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        // 商品の購入者を更新
        $item->update(['buyer_id' => $user->id]);

        return redirect('/mypage')->with('success', '決済が完了しました！');
    }

    // キャンセル時
    public function cancel()
    {
        return redirect('/mypage')->with('success', '決済がキャンセルされました。');
    }
}
