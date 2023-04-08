<?php

use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Http\UploadedFile;

it('should not be able to access any products route if not authenticated', function () {
    $this->get('/products')
        ->assertRedirect('/login');
});

it('returns a list of products', function () {
    Product::factory()->count(5)->create();

    $this->actingAs(User::factory()->create())
        ->get('/products')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Products/Index')
            ->has('products', fn (Assert $page) => $page
                ->has('total')
                ->has('per_page')
                ->has('current_page')
                ->has('last_page')
                ->has('links')
                ->has('first_page_url')
                ->has('last_page_url')
                ->has('next_page_url')
                ->has('prev_page_url')
                ->has('path')
                ->has('from')
                ->has('to')
                ->has('data', 5, fn (Assert $page) => $page
                    ->has('id')
                    ->has('name')
                    ->has('slug')
                    ->has('description')
                    ->has('price')
                    ->has('image')
                    ->has('created_at')
                    ->has('updated_at')
                )
            )
        );
});

it('can not create a product with invalid data', function () {
    $this->actingAs(User::factory()->create())
        ->post('/products', [
            'name' => '',
            'slug' => '',
            'description' => '',
            'price' => '',
            'image' => '',
        ])
        ->assertRedirect()
        ->assertInvalid(['name', 'price']);
});

it('can not create a product with a larger image', function () {
    Storage::fake('local');
    $file = UploadedFile::fake()->image('product.png', 600, 600)->size(5000);

    $this->actingAs(User::factory()->create())
        ->post('/products', [
            'name' => 'Product name',
            'description' => 'Product description',
            'price' => 10.40,
            'image' => $file,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['image']);
});

it('can not create a product with a non image file', function () {
    Storage::fake('local');
    $file = UploadedFile::fake()->create('product.pdf', 500);

    $this->actingAs(User::factory()->create())
        ->post('/products', [
            'name' => 'Product name',
            'description' => 'Product description',
            'price' => 10.40,
            'image' => $file,
        ])
        ->assertRedirect()
        ->assertSessionHasErrors(['image']);
});

it('can create a product', function () {
    Storage::fake('local');
    $file = UploadedFile::fake()->image('product.png', 600, 600)->size(500);

    $this->actingAs(User::factory()->create())
        ->post('/products', [
            'name' => 'Product name',
            'description' => 'Product description',
            'price' => 10.40,
            'image' => $file,
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('products', [
        'name' => 'Product name',
        'slug' => 'product-name',
        'description' => 'Product description',
        'price' => 10.40,
        'image' => 'products/' . $file->hashName(),
    ]);
});

it('creates a product when a slug already exists', function () {
    Storage::fake('local');
    $file = UploadedFile::fake()->image('product.png', 600, 600)->size(500);
    $payload = [
        'name' => 'Product name',
        'description' => 'Product description',
        'price' => 10.40,
        'image' => $file,
    ];

    $this->actingAs(User::factory()->create())
        ->post('/products', $payload)
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->actingAs(User::factory()->create())
        ->post('/products', $payload)
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->assertDatabaseCount('products', 2);
});

it('can not update a product with invalid data', function () {
    $product = Product::factory()->create();

    $this->actingAs(User::factory()->create())
        ->put('/products/' . $product->id, [
            'name' => '',
            'slug' => '',
            'description' => '',
            'price' => '',
            'image' => '',
        ])
        ->assertRedirect()
        ->assertInvalid(['name', 'price']);
});

it('can update a product', function () {
    $product = Product::factory()->create();

    $this->actingAs(User::factory()->create())
        ->put('/products/' . $product->id, [
            'name' => 'Product name',
            'description' => 'Product description',
            'price' => 10.40,
        ])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Product name',
        'slug' => $product->slug,
        'description' => 'Product description',
        'price' => 10.40,
    ]);
});

it('can not delete a product with invalid id', function () {
    $this->actingAs(User::factory()->create())
        ->delete('/products/invalid-id')
        ->assertNotFound();
});

it('can delete a product', function () {
    $product = Product::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete("/products/{$product->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
