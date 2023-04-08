<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Exception;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $service,
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Inertia::render('Categories/Index', [
                'categories' => $this->service->fetchAll(),
            ]);
        } catch (Throwable $th) {
            $this->handleError($th, 'An error occurred while trying to list the categories.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $this->service->create($request->validated());

            return redirect()
                ->route('categories.index')
                ->withSuccess('Category created successfully.');

        } catch (Throwable $th) {
            $this->handleError($th, 'Category could not be created.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $isUpdated = $this->service->update($category, $request->validated());

            if (!$isUpdated) {
                throw new Exception("It was not possible to update the category.");
            }

            return redirect()
                ->route('categories.index')
                ->withSuccess('Category updated successfully.');
        } catch (Throwable $th) {
            $this->handleError($th, 'Category could not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $isDeleted = $this->service->delete($category);

            if (!$isDeleted) {
                throw new Exception("It was not possible to delete the category.");

            }

            return redirect()
                    ->route('categories.index')
                    ->withSuccess('Category deleted successfully.');
        } catch (Throwable $th) {
            $this->handleError($th, 'Category could not be deleted.');
        }
    }
}
