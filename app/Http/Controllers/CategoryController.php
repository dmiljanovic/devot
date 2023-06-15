<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\ShowAndDeleteCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


/**
 * @group Category CRUD
 *
 * API endpoints for managing Category CRUD operations
 */
class CategoryController extends Controller
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories with pagination
     *
     * @response {"current_page": 1,
     *  "data": [
     *   {
     *    "id": 1,
     *    "name": "Food",
     *    "created_at": null,
     *    "updated_at": null
     *   },
     *   {
     *    "id": 2,
     *    "name": "Household items",
     *    "created_at": null,
     *    "updated_at": null
     *   }
     *  ],
     *  "first_page_url": "http://devot.test/api/categories?page=1",
     *  "from": 1,
     *  "last_page": 4,
     *  "last_page_url": "http://devot.test/api/categories?page=4",
     *  "links": [
     *   {
     *    "url": null,
     *    "label": "&laquo; Previous",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/categories?page=1",
     *    "label": "1",
     *    "active": true
     *   },
     *   {
     *    "url": "http://devot.test/api/categories?page=2",
     *    "label": "2",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/categories?page=3",
     *    "label": "3",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/categories?page=4",
     *    "label": "4",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/categories?page=2",
     *    "label": "Next &raquo;",
     *    "active": false
     *   }
     *  ],
     *  "next_page_url": "http://devot.test/api/categories?page=2",
     *  "path": "http://devot.test/api/categories",
     *  "per_page": 2,
     *  "prev_page_url": null,
     *  "to": 2,
     *  "total": 7
     * }
     */
    public function index(Request $request): ?JsonResponse
    {
        try {
            $categories = $this->categoryRepository->getAllWithPagination($request);
        } catch (\Exception $e) {
            Log::error('Error while getting all categories with pagination: ' . $e);

            return response()->json('Error while getting all categories with pagination.', 500);
        }

        return response()->json($categories);
    }

    /**
     * Store new category.
     *
     * @response {
     *  "message": "Category successfully stored.",
     * }
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $this->categoryRepository->store($request->validated());
        } catch (\Exception $e) {
            Log::error('Error while storing category: ' . $e);

            return response()->json('Error while storing category.', 500);
        }

        return response()->json('Category successfully stored.');
    }

    /**
     * Show single category.
     *
     * @response {
     *  "id": 1,
     *  "name": "Food",
     *  "created_at": null,
     *  "updated_at": null
     * }
     */
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

    /**
     * Update a category.
     *
     * @response {
     *  "message": "Category successfully updated.",
     * }
     *
     */
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

    /**
     * Delete a category.
     *
     * @response {
     *  "message": "Category successfully deleted.",
     * }
     */
    public function destroy(ShowAndDeleteCategoryRequest $request): JsonResponse
    {
        try {
            $this->categoryRepository->delete($request->get('category'));
        } catch (\Exception $e) {
            Log::error('Error while deleting category: ' . $e);

            return response()->json('Error while deleting category.', 500);
        }

        return response()->json('Category successfully deleted.');
    }
}
