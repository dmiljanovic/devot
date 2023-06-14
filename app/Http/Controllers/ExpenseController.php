<?php

namespace App\Http\Controllers;

use App\Events\ExpenseCreated;
use App\Http\Requests\Expense\ShowAndDeleteExpenseRequest;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    private ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $expenses = $this->expenseRepository->getAllWithPagination();
        } catch (\Exception $e) {
            Log::error('Error while getting all expenses with pagination: ' . $e);

            return response()->json('Error while getting all expenses with pagination.', 500);
        }

        return response()->json($expenses);
    }

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

        return response()->json('Expense successfully stored.');
    }

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

    public function update(UpdateExpenseRequest $request): JsonResponse
    {
        try {
            $this->expenseRepository->update($request->validated());
        } catch (\Exception $e) {
            Log::error('Error while updating the category: ' . $e);

            return response()->json('Error while updating the expense.', 500);
        }

        return response()->json('Expense successfully updated.');
    }

    public function destroy(ShowAndDeleteExpenseRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $this->expenseRepository->delete($request->get('expense'));
            //TODO revert calculation for user total amount.
        } catch (\Exception $e) {
            Log::error('Error while deleting category: ' . $e);
            DB::rollBack();

            return response()->json('Error while deleting expense.', 500);
        }

        DB::commit();

        return response()->json('Expense Successfully deleted.');
    }
}
