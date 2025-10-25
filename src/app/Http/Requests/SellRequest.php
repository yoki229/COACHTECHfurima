<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required|max:255',
            'item_image' => 'required|file|mimes:jpeg,png',
            'category' => 'required|array',
            'status' => 'required',
            'price' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max'      => '商品説明は255文字以内で入力してください',
            'item_image.required'       => '商品画像を選択してください',
            'item_image.mimes'          => '商品画像はjpegまたはpng形式を選択してください',
            'category.required'  => 'カテゴリーを選択してください',
            'status.required'      => '商品の状態を選択してください',
            'price.required'       => '価格を入力してください',
            'price.integer'        => '商品価格は数値で入力してください',
            'price.min'            => '商品価格は0円以上で入力してください',
        ];
    }
}
