<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NavigationController extends Controller
{
    public function __construct(
        private ProductService $productService,
    ){}

    public function index() {
        return Inertia::render('Shop', [
            'products' => $this->productService->fetchPaginated(),
        ]);
    }
}
