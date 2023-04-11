<?php

namespace App\Http\Middleware;

use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function __construct(
        private CartService $cartService,
    ){}

    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $cookie = $request->cookie('cart_id');
        return array_merge(parent::share($request), [
            // This shares the cart items count with all Inertia pages.
            'cart_count' => $cookie ? $this->cartService->count($cookie) : 0,
            'cart' => fn () => $request->session()->get('cart'),
            'flash' => [
                'cart' => fn () => $request->session()->get('cart'),
            ],
        ]);
    }
}
