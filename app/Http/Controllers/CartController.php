<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Arr;

class CartController extends Controller
{
    public function storeCart(Request $request)
    {
        $request->session()->put('cart', $request->input('cart'));

        return response()->json(['success' => true]);

    }

    public function showCart(Request $request)
    {
        $sessionCart = $request->session()->get('cart', []);

        if (!is_array($sessionCart)) $sessionCart = [];

        // Gather unique product ids to avoid N+1
        $productIds = collect($sessionCart)->pluck('product_id')->unique()->filter()->values()->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = [];
        $total = 0;

        foreach ($sessionCart as $key => $item) {
            $pid = (int)($item['product_id'] ?? 0);
            $product = $products->get($pid);

            // fallback price if product not found
            $price = $product ? ($product->price ?? 0) : 0;
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $subtotal = $price * $quantity;
            $total += $subtotal;

            $frontImage = null;
            if ($product) {
                $colorId = $item['color_id'] ?? null;
                $frontImageRow = $product->images()
                    ->where('image_type', 'front')
                    ->when($colorId, fn($q) => $q->where('color_id', $colorId))
                    ->latest()
                    ->first();
                $frontImage = $frontImageRow->image_path ?? null;
            }

            $cartItems[] = [
                'key' => $key,
                'product_id' => $pid,
                'product' => $product, // may be null
                'product_name' => $item['product_name'] ?? ($product->name ?? 'Unknown Product'),
                'product_image' => $frontImage ?? ($item['product_image'] ?? $product->feature_image ?? null),
                'ean' => $item['ean'] ?? null,
                'size_id' => $item['size_id'] ?? null,
                'color_id' => $item['color_id'] ?? null,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
                'customization' => $item['customization'] ?? [],
            ];
        }

        return view('frontend.cart', [
            'cartItems' => $cartItems,
            'total' => $total,
            'currency' => '£',
        ]);
    }

    public function updateCartItem(Request $request)
    {
        $key = $request->input('key');
        $quantity = max(1, (int)$request->input('quantity', 1));

        $cart = $request->session()->get('cart', []);
        if (!is_array($cart)) $cart = [];

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);
        }

        // Return new totals (recompute)
        $response = $this->recomputeCartTotals($request);
        return response()->json($response);
    }

    public function removeCartItem(Request $request)
    {
        $key = $request->input('key');

        $cart = $request->session()->get('cart', []);
        if (!is_array($cart)) $cart = [];

        if (isset($cart[$key])) {
            unset($cart[$key]);
            $request->session()->put('cart', $cart);
        }

        $response = $this->recomputeCartTotals($request);
        return response()->json($response);
    }

    // Helper to recompute totals and return minimal payload for front-end
    protected function recomputeCartTotals(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (!is_array($cart)) $cart = [];

        $productIds = collect($cart)->pluck('product_id')->unique()->filter()->values()->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;
        foreach ($cart as $key => $item) {
            $pid = (int)($item['product_id'] ?? 0);
            $product = $products->get($pid);
            $price = $product ? ($product->price ?? 0) : 0;
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $subtotal = $price * $quantity;
            $total += $subtotal;

            $items[$key] = [
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        }

        return [
            'items' => $items,
            'total' => $total,
            'currency' => '£',
            'items_count' => count($cart),
        ];
    }









}
