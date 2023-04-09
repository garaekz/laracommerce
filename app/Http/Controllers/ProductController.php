<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;
use Illuminate\Support\Str;

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
            $code = Str::random(6);
            Log::error("[{$code}] - Error message: {$th}");
            return redirect()
                ->back()
                ->withError("An error occurred while trying to list the products. Error code: {$code}");
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
            $code = Str::random(6);
            Log::error("[{$code}] - Error message: {$th}");
            return redirect()
                ->back()
                ->withError("Product could not be created. Error code: {$code}");
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
            $code = Str::random(6);
            Log::error("[{$code}] - Error message: {$th}");
            return redirect()
                ->back()
                ->withError("Product could not be updated. Error code: {$code}");
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
            $code = Str::random(6);
            Log::error("[{$code}] - Error message: {$th}");
            return redirect()
                ->back()
                ->withError("Product could not be deleted. Error code: {$code}");
        }
    }
}
