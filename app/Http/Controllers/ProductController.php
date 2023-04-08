<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Inertia\Inertia;
use Throwable;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $service,
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Inertia::render('Products/Index', [
                'products' => $this->service->fetchPaginated(),
            ]);
        } catch (Throwable $th) {
            handleControllerException($th, 'An error occurred while trying to list the products.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $this->service->create($request->validated());

            return redirect()
                ->route('products.index')
                ->withSuccess('Product created successfully.');
        } catch (Throwable $th) {
            handleControllerException($th, 'Product could not be created.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->service->update($product, $request->validated());

            return redirect()
                ->route('products.index')
                ->withSuccess('Product updated successfully.');
        } catch (Throwable $th) {
            handleControllerException($th, 'Product could not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->service->delete($product);

            return redirect()
                ->route('products.index')
                ->withSuccess('Product deleted successfully.');
        } catch (Throwable $th) {
            handleControllerException($th, 'Product could not be deleted.');
        }
    }
}
