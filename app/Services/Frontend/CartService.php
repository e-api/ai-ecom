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
        $variantId = $data['variant_id'] ?? null;
        $variantId = $variantId === '' ? null : $variantId;
        $quantity = (int) $data['quantity'];
        $variant = null;

        if ($quantity < 1) {
            throw new \Exception('Please enter a valid quantity.');
        }

        if (!empty($variantId)) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('status', 1)
                ->findOrFail($variantId);
        }

        $availableStock = (int) ($variant ? $variant->stock : $product->stock);

        if ($availableStock < $quantity) {
            throw new \Exception('Requested quantity is not available.');
        }

        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
            ]);

            $newQuantity = ($cartItem->exists ? (int) $cartItem->quantity : 0) + $quantity;

            if ($availableStock < $newQuantity) {
                throw new \Exception('Requested quantity is not available.');
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();

            return true;
        }

        $cart = session()->get('cart', []);
        $key = $product->id . '_' . ($variantId ?: 0);
        $currentQuantity = isset($cart[$key]) ? (int) $cart[$key]['quantity'] : 0;
        $newQuantity = $currentQuantity + $quantity;

        if ($availableStock < $newQuantity) {
            throw new \Exception('Requested quantity is not available.');
        }

        $cart[$key] = [
            'product_id' => $product->id,
            'product_variant_id' => $variantId,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $product->image,
            'price' => $variant ? $variant->price : ($product->sale_price ?? $product->price),
            'quantity' => $newQuantity,
            'variant_size' => $variant?->size,
            'sku' => $variant?->sku ?? $product->sku,
            'color' => $product->color,
            'service_provider' => $product->service_provider,
            'product_grade' => $product->product_grade,
        ];

        session()->put('cart', $cart);

        return true;
    }

    public function moveSessionCartToDatabase()
    {
        if (!auth()->check()) {
            return;
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return;
        }

        foreach ($cart as $item) {
            $variantId = $item['product_variant_id'] ?? null;
            $query = CartItem::where('user_id', auth()->id())
                ->where('product_id', $item['product_id']);

            if ($variantId) {
                $query->where('product_variant_id', $variantId);
            } else {
                $query->whereNull('product_variant_id');
            }

            $cartItem = $query->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $item['quantity']);
            } else {
                CartItem::create([
                    'user_id' => auth()->id(),
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $variantId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        session()->forget('cart');
    }

    public function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::with(['product', 'variant'])
                ->where('user_id', Auth::id())
                ->get()
                ->map(fn ($item) => $this->formatCartItem(
                    $item->product,
                    $item->variant,
                    (int) $item->quantity,
                    $item->id
                ));
        }

        return collect(session('cart', []))
            ->map(function ($item, $key) {
                $product = Product::find($item['product_id'] ?? null);
                $variantId = $item['product_variant_id'] ?? null;
                $variant = $variantId ? ProductVariant::find($variantId) : null;

                return $this->formatCartItem(
                    $product,
                    $variant,
                    (int) ($item['quantity'] ?? 1),
                    $key,
                    $item
                );
            })
            ->filter(fn ($item) => !empty($item['product_id']))
            ->values();
    }

    public function getCartTotal()
    {
        return $this->getCartItems()->sum('line_total');
    }

    public function getCartCount()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())
                ->sum('quantity');
        }

        $count = 0;

        foreach (session('cart', []) as $item) {
            $count += (int) $item['quantity'];
        }

        return $count;
    }

    private function formatCartItem(?Product $product, ?ProductVariant $variant, int $quantity, string|int $key, array $fallback = []): array
    {
        $quantity = max(1, $quantity);
        $price = (float) ($variant?->price ?? $product?->sale_price ?? $product?->price ?? $fallback['price'] ?? 0);

        return [
            'key' => $key,
            'product_id' => $product?->id ?? $fallback['product_id'] ?? null,
            'product_variant_id' => $variant?->id ?? $fallback['product_variant_id'] ?? null,
            'name' => $product?->name ?? $fallback['name'] ?? 'Unavailable product',
            'slug' => $product?->slug ?? $fallback['slug'] ?? null,
            'image' => $product?->image ?? $fallback['image'] ?? null,
            'price' => $price,
            'quantity' => $quantity,
            'line_total' => $price * $quantity,
            'variant_size' => $variant?->size ?? $fallback['variant_size'] ?? null,
            'sku' => $variant?->sku ?? $product?->sku ?? $fallback['sku'] ?? null,
            'color' => $product?->color ?? $fallback['color'] ?? null,
            'service_provider' => $product?->service_provider ?? $fallback['service_provider'] ?? null,
            'product_grade' => $product?->product_grade ?? $fallback['product_grade'] ?? null,
        ];
    }
}
