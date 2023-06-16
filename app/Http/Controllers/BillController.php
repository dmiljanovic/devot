<?php

namespace App\Http\Controllers;

use App\Events\BillDeleted;
use App\Http\Requests\Bill\ShowAndUpdateBillRequest;
use App\Repositories\BillRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillController extends Controller
{
    private BillRepository $billRepository;

    public function __construct(BillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $categories = $this->billRepository->getAllWithPagination($request);
        } catch (\Exception $e) {
            Log::error('Error while getting all bills with pagination: ' . $e);

            return response()->json('Error while getting all bills with pagination.', 500);
        }

        return response()->json($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param ShowAndUpdateBillRequest $request
     * @return JsonResponse
     */
    public function show(ShowAndUpdateBillRequest $request): JsonResponse
    {
        try {
            $bill = $this->billRepository->getById($request->get('bill'), true);
        } catch (\Exception $e) {
            Log::error('Error while getting bill: ' . $e);

            return response()->json('Error while getting bill.', 500);
        }

        return response()->json($bill);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ShowAndUpdateBillRequest $request
     * @return JsonResponse
     */
    public function destroy(ShowAndUpdateBillRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $bill = $this->billRepository->delete($request->get('bill'));
            BillDeleted::dispatch($bill);
        } catch (\Exception $e) {
            Log::error('Error while deleting category: ' . $e);
            DB::rollBack();

            return response()->json('Error while deleting category.', 500);
        }

        DB::commit();

        return response()->json('Bill successfully deleted.');
    }
}
