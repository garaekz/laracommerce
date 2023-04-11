<?php

namespace App\Http\Controllers;

use App\Helpers\CustomStr;
use App\Models\Cart;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Str;
use Inertia\Inertia;

const CART_COOKIE_NAME = 'cart_id';
const CART_COOKIE_DURATION = 60 * 24 * 7;
class CartController extends Controller
{
    public function __construct(
        private CartService $service,
    ){}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        try {
            $cookie = $request->cookie(CART_COOKIE_NAME);
            if (!$cookie) {
                $cookie = cookie(CART_COOKIE_NAME, CustomStr::ulid(), CART_COOKIE_DURATION);
            }

            $data = $request->validated();
            if (auth()->check()) {
                $data['user_id'] = auth()->id();
            }

            $this->service->update($data, $cookie);

            return redirect()
                ->back()
                ->withCookie($cookie)
                ->with([
                    'cart' => $this->service->getWithItems($cookie),
                    'success' => 'Cart updated.'
                ]);

        } catch (Throwable $th) {
            $code = Str::random(6);
            Log::error("[{$code}] - Error message: {$th}");
            return redirect()
                ->back()
                ->withError("Product could not be added to cart. Error code: {$code}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {
            $cookie = request()->cookie(CART_COOKIE_NAME);
            if (!$cookie) {
                throw new Exception('Cart not found.');
            }

            $cart = $this->service->getWithItems($cookie);

            return redirect()
                ->back()
                ->withCookie($cookie)
                ->withSuccess('Cart updated.')
                ->with('cart', $cart);
        } catch (Throwable $th) {
            $code = Str::random(6);
            Log::error("[{$code}] - Error message: {$th}");
            return redirect()
                ->back()
                ->withCookie($cookie)
                ->withError('Error while getting cart.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        try {
            $cookie = request()->cookie(CART_COOKIE_NAME);
            if(!$cookie) {
                throw new Exception('Cart not found.');
            }

            if (!$this->service->delete($cookie)) {
                throw new Exception('Cart could not be deleted.');
            }

            Cookie::forget(CART_COOKIE_NAME);
            return redirect()
                ->back()
                ->withSuccess('Cart deleted.');
        } catch (Exception $e) {
            $code = Str::random(6);
            Log::error("[{$code}] - Error message: {$e}");
            return redirect()
                ->back()
                ->withError("Cart could not be deleted. Error code: {$code}");
        }
    }
}
