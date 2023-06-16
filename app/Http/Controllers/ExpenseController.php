<?php

namespace App\Http\Controllers;

use App\Events\ExpenseCreated;
use App\Events\ExpenseDeleted;
use App\Http\Requests\Expense\AggregateExpenseRequest;
use App\Http\Requests\Expense\ShowAndDeleteExpenseRequest;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @group Expense CRUD
 *
 * API endpoints for managing Expense CRUD operations
 */
class ExpenseController extends Controller
{
    private ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Get all expenses with pagination
     *
     * @queryParam category int Filter by ID of a category. Example 1.
     * @queryParam price_min int Filter by minimum amount of an expense. Example: 100
     * @queryParam price_max int Filter by maximum amount of an expense. Example: 200
     * @queryParam date_from date string Filter by date created. Example: 20-5-2023
     * @queryParam date_to date string Filter by date created. Example: 25-5-2023
     *
     * @response {"current_page": 1,
     *  "data": [
     *  {
     *    "id": 1,
     *    "user_id": 1,
     *    "category_id": 1,
     *    "description": "Test description...",
     *    "amount": "196.00",
     *    "created_at": "2023-06-15T12:03:40.000000Z",
     *    "updated_at": null
     *   },
     *   {
     *    "id": 2,
     *    "user_id": 1,
     *    "category_id": 2,
     *    "description": "Test description...",
     *    "amount": "4893.00",
     *    "created_at": "2023-06-15T12:03:40.000000Z",
     *    "updated_at": null
     *   }
     *  ],
     *  "first_page_url": "http://devot.test/api/expenses?page=1",
     *  "from": 1,
     *  "last_page": 4,
     *  "last_page_url": "http://devot.test/api/expenses?page=4",
     *  "links": [
     *   {
     *    "url": null,
     *    "label": "&laquo; Previous",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/expenses?page=1",
     *    "label": "1",
     *    "active": true
     *   },
     *   {
     *    "url": "http://devot.test/api/expenses?page=2",
     *    "label": "2",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/expenses?page=3",
     *    "label": "3",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/expenses?page=4",
     *    "label": "4",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/expenses?page=2",
     *    "label": "Next &raquo;",
     *    "active": false
     *   }
     *  ],
     *  "next_page_url": "http://devot.test/api/expenses?page=2",
     *  "path": "http://devot.test/api/expenses",
     *  "per_page": 2,
     *  "prev_page_url": null,
     *  "to": 2,
     *  "total": 7
     * }
     * @authenticated
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $expenses = $this->expenseRepository->getAllWithPagination($request);
        } catch (\Exception $e) {
            Log::error('Error while getting all expenses with pagination: ' . $e);

            return response()->json('Error while getting all expenses with pagination.', 500);
        }

        return response()->json($expenses);
    }

    /**
     * Store new expense.
     *
     * @response {
     *  "message": "Expense successfully stored.",
     * }
     * @authenticated
     */
    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['user_id' => $request->user()->id]);

        DB::beginTransaction();

        try {
            $expense = $this->expenseRepository->store($data);
            ExpenseCreated::dispatch($expense);
        } catch (\Exception $e) {
            Log::error('Error while storing expense: ' . $e);
            DB::rollBack();

            return response()->json('Error while storing expense.', 500);
        }

        DB::commit();

        return response()->json(['message' => 'Expense successfully stored.', 'expense' => $expense]);
    }

    /**
     * Show single expense.
     *
     * @response {
     *   "id": 2,
     *   "user_id": 1,
     *   "category_id": 2,
     *   "description": "Test description...",
     *   "amount": "4893.00",
     *   "created_at": "2023-06-15T12:03:40.000000Z",
     *   "updated_at": null
     *  }
     * @authenticated
     */
    public function show(ShowAndDeleteExpenseRequest $request): JsonResponse
    {
        try {
            $expense = $this->expenseRepository->getById($request->get('expense'));
        } catch (\Exception $e) {
            Log::error('Error while getting expense: ' . $e);

            return response()->json('Error while getting expense.', 500);
        }

        return response()->json($expense);
    }

    /**
     * Update an expense.
     *
     * @response {
     *  "message": "Expense successfully updated.",
     * }
     * @authenticated
     */
    public function update(UpdateExpenseRequest $request): JsonResponse
    {
        try {
            $this->expenseRepository->update($request->validated());
        } catch (\Exception $e) {
            Log::error('Error while updating the expense: ' . $e);

            return response()->json('Error while updating the expense.', 500);
        }

        return response()->json('Expense successfully updated.');
    }

    /**
     * Delete an expense.
     *
     * @response {
     *  "message": "Expense successfully deleted.",
     * }
     * @authenticated
     */
    public function destroy(ShowAndDeleteExpenseRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $expense = $this->expenseRepository->delete($request->get('expense'));
            ExpenseDeleted::dispatch($expense);
        } catch (\Exception $e) {
            Log::error('Error while deleting expense: ' . $e);
            DB::rollBack();

            return response()->json('Error while deleting expense.', 500);
        }

        DB::commit();

        return response()->json('Expense successfully deleted.');
    }


    /**
     * Get aggregate expenses.
     *
     * @response {
     *  "34602.00",
     * }
     * @authenticated
     */
    public function aggregate(AggregateExpenseRequest $request): JsonResponse
    {
        try {
            $expenses = $this->expenseRepository->getAggregateByTerm($request->validated('term'));
        } catch (\Exception $e) {
            Log::error('Error while getting aggregate expenses: ' . $e);

            return response()->json('Error while getting aggregate expenses.', 500);
        }

        return response()->json($expenses);
    }
}
