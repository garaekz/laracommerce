<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NavigationController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CartService $cartService,
    ){}

    public function index(Request $request) {
        $cookie = $request->cookie('cart_id');
        return Inertia::render('Shop', [
            'products' => $this->productService->fetchPaginated(),
            'cart' => $request->cart && $cookie ? $this->cartService->getWithItems($cookie) : null,
        ]);
    }
}
