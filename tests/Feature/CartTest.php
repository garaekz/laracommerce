<?php

use App\Helpers\CustomStr;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('validates if quantity is a negative number', function () {
    $product = Product::factory()->create();

    $this
        ->post('/cart', [
            'product_id' => $product->id,
            'quantity' => -1,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('quantity');
});

it('redirects with cookie if no cookie is present', function () {
    $product = Product::factory()->create();

    $this
        ->post('/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])
        ->assertRedirect()
        ->assertCookie('cart_id')
        ->assertSessionHas('success', 'Cart updated.');
});

it('redirects without cookie if cookie is present', function () {
    $product = Product::factory()->create();

    $this
        ->withCookie('cart_id', CustomStr::ulid())
        ->post('/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])
        ->assertRedirect()
        ->assertCookieMissing('cart_id')
        ->assertSessionHas('success', 'Cart updated.');
});

it('can add a product to the cart as a logged in user', function () {
    $product = Product::factory()->create();
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->post('/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])
        ->assertRedirect()
        ->assertCookie('cart_id')
        ->assertSessionHas('success', 'Cart updated.');

    $cart = $user->cart;
    $this->assertDatabaseHas('carts', [
        'user_id' => auth()->id(),
        'cookie_cart_id' => $cart->cookie_cart_id,
    ]);

    $this->assertDatabaseHas('cart_items', [
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);
});

it('can add a product to the cart as a guest', function () {
    $product = Product::factory()->create();

    $this
        ->post('/cart', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])
        ->assertRedirect()
        ->assertCookie('cart_id')
        ->assertSessionHas('success', 'Cart updated.');

    $this->assertDatabaseHas('carts', [
        'user_id' => null,
    ]);

    $this->assertDatabaseCount('cart_items', 1);
});

it('can update cart item quantity', function () {
    $product = Product::factory()->create();
    $cart_id = CustomStr::ulid();
    $cart = Cart::factory()->create([
        'user_id' => null,
        'cookie_cart_id' => $cart_id,
    ]);

    $this
        ->withCookie('cart_id', $cart_id)
        ->post('/cart', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Cart updated.');

    $this->assertDatabaseHas('cart_items', [
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->assertDatabaseCount('cart_items', 1);
});

it('deletes the cart item if quantity is 0', function () {
    $product = Product::factory()->create();
    $cart_id = CustomStr::ulid();
    $cart = Cart::factory()->create([
        'user_id' => null,
        'cookie_cart_id' => $cart_id,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $this
        ->withCookie('cart_id', $cart_id)
        ->post('/cart', [
            'product_id' => $product->id,
            'quantity' => 0,
        ])
        ->assertRedirect()
        ->assertSessionHas('success', 'Cart updated.');

    $this->assertDatabaseMissing('cart_items', [
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 0,
    ]);

    $this->assertDatabaseCount('cart_items', 0);
});

it('can delete the cart and items', function () {
    $product = Product::factory()->create();
    $cart_id = CustomStr::ulid();
    $cart = Cart::factory()->create([
        'user_id' => null,
        'cookie_cart_id' => $cart_id,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $this
        ->withCookie('cart_id', $cart_id)
        ->delete('/cart')
        ->assertRedirect()
        ->assertSessionHas('success', 'Cart deleted.');

    $this->assertDatabaseMissing('carts', [
        'id' => $cart->id,
    ]);

    $this->assertDatabaseMissing('cart_items', [
        'cart_id' => $cart->id,
    ]);

    $this->assertDatabaseCount('carts', 0);
    $this->assertDatabaseCount('cart_items', 0);
});

it('shows the cart with all his items', function () {
    $cart_id = CustomStr::ulid();
    $cart = Cart::factory()->create([
        'user_id' => null,
        'cookie_cart_id' => $cart_id,
    ]);

    CartItem::factory(5)->create([
        'cart_id' => $cart->id,
        'quantity' => 1,
    ]);

    $this
        ->withCookie('cart_id', $cart_id)
        ->get('/cart')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
                ->component('Cart')
                ->has('cart',fn (Assert $page) => $page
                        ->has('id')
                        ->has('user_id')
                        ->has('cookie_cart_id')
                        ->has('created_at')
                        ->has('updated_at')
                        ->has('items', 5, fn (Assert $page) => $page
                                ->has('id')
                                ->has('cart_id')
                                ->has('product_id')
                                ->has('quantity')
                                ->has('created_at')
                                ->has('updated_at')
                        )
                )
        );
});
