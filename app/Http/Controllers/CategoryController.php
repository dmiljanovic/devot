<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\ShowAndDeleteCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(): ?JsonResponse
    {
        try {
            $categories = $this->categoryRepository->getAllWithPagination();
        } catch (\Exception $e) {
            Log::error('Error while getting all categories with pagination: ' . $e);

            return response()->json('Error while getting all categories with pagination.', 500);
        }

        return response()->json($categories);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $this->categoryRepository->store($request->validated());
        } catch (\Exception $e) {
            Log::error('Error while storing category: ' . $e);

            return response()->json('Error while storing category.', 500);
        }

        return response()->json('Category Successfully stored.');
    }

    public function show(ShowAndDeleteCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryRepository->getById($request->get('category'));
        } catch (\Exception $e) {
            Log::error('Error while getting category: ' . $e);

            return response()->json('Error while getting category.', 500);
        }

        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request): JsonResponse
    {
        try {
            $this->categoryRepository->update($request->validated());
        } catch (\Exception $e) {
            Log::error('Error while updating the category: ' . $e);

            return response()->json('Error while updating the category.', 500);
        }

        return response()->json('Category successfully updated.');
    }

    public function destroy(ShowAndDeleteCategoryRequest $request): JsonResponse
    {
        try {
            $this->categoryRepository->delete($request->get('category'));
        } catch (\Exception $e) {
            Log::error('Error while deleting category: ' . $e);

            return response()->json('Error while deleting category.', 500);
        }

        return response()->json('Category Successfully deleted.');
    }
}
