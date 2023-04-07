<?php

use App\Models\Category;
use Inertia\Testing\AssertableInertia as Assert;

it('returns a list of categories', function () {
    Category::factory()->count(5)->create();

    $this->get('/categories')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Categories/Index')
            ->has('categories', 5, fn (Assert $page) => $page
                ->has('id')
                ->has('name')
                ->has('slug')
                ->has('created_at')
                ->has('updated_at')
            )
        );
});

it('can not create a category with invalid data', function () {
    // The only error is the slug
    $this->post('/categories', [
        'name' => 'New name',
        'slug' => '',
        'description' => '',
    ])
        ->assertRedirect()
        ->assertInvalid(['slug']);
});

it('can create a new category', function () {
    $this->post('/categories', [
        'name' => 'Category',
        'slug' => 'category',
    ])
        ->assertRedirect('/categories')
        ->assertSessionHas('success', 'Category created successfully.');

    $this->assertDatabaseHas('categories', [
        'name' => 'Category',
        'slug' => 'category',
    ]);
});

it('can not update a category with invalid data', function () {
    $category = Category::factory()->create();

    $this->put("/categories/{$category->id}", [
        'name' => '',
        'slug' => '',
    ])
        ->assertRedirect()
        ->assertInvalid(['name', 'slug']);
});

it('can update a category', function () {
    $category = Category::factory()->create([
        'name' => 'Category',
        'slug' => 'category',
    ]);

    $this->put("/categories/{$category->id}", [
        'name' => 'Updated Category',
        'slug' => 'category',
    ])
        ->assertRedirect('/categories')
        ->assertSessionHas('success', 'Category updated successfully.');

    $this->assertDatabaseHas('categories', [
        'name' => 'Updated Category',
        'slug' => 'category',
    ]);
});

it('can not delete a category when id is invalid', function () {
    $this->delete('/categories/invalid-id')
        ->assertNotFound();
});

it('can delete a category', function () {
    $category = Category::factory()->create();

    $this->delete("/categories/{$category->id}")
        ->assertRedirect('/categories')
        ->assertSessionHas('success', 'Category deleted successfully.');

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});
