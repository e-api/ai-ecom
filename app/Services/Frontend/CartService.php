<?php

namespace App\Services\Frontend;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function add($data)
    {
        $product = Product::findOrFail($data['product_id']);
        $variant = null;

        if (!empty($data['variant_id'])) {
            $variant = ProductVariant::findOrFail($data['variant_id']);

            if ($variant->stock < $data['quantity']) {
                throw new \Exception('Requested quantity is not available.');
            }
        }

        /*
        |---
        | Logged In User
        |---
        */
        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'product_variant_id' => $data['variant_id'] ?? null,
            ]);

            if ($cartItem->exists) {
                $cartItem->quantity += $data['quantity'];
            } else {
                $cartItem->quantity = $data['quantity'];
            }

            $cartItem->save();

            return true;
        }

        /*
        |---
        | Guest User
        |---
        */
        $cart = session()->get('cart', []);
        $key = $product->id . '_' . ($data['variant_id'] ?? 0);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $data['quantity'];
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'variant_id' => $data['variant_id'] ?? null,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $variant ? $variant->price : ($product->sale_price ?? $product->price),
                'quantity' => $data['quantity'],
            ];
        }

        session()->put('cart', $cart);

        return true;
    }
}