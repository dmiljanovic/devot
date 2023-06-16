<?php

namespace App\Http\Controllers;

use App\Events\BillDeleted;
use App\Http\Requests\Bill\ShowAndUpdateBillRequest;
use App\Repositories\BillRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @group Bill
 *
 * API endpoints for bill reads and delete
 */
class BillController extends Controller
{
    private BillRepository $billRepository;

    public function __construct(BillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    /**
     * Get all bills with pagination
     *
     * @queryParam category int Filter by ID of an expense category. Example 1.
     * @queryParam price_min int Filter by minimum amount of a bill. Example: 100
     * @queryParam price_max int Filter by maximum amount of a bill. Example: 200
     * @queryParam date_from date string Filter by date created. Example: 20-5-2023
     * @queryParam date_to date string Filter by date created. Example: 25-5-2023
     *
     * @response {"current_page": 1,
     *  "data": [
     *   {
     *    "id": 1,
     *    "user_id": 1,
     *    "amount": "36740.00",
     *    "created_at": null,
     *    "updated_at": "2023-06-16T13:51:34.000000Z"
     *   },
     *  {
     *    "id": 2,
     *    "user_id": 1,
     *    "amount": "2201.50",
     *    "created_at": "2023-06-16T13:52:09.000000Z",
     *    "updated_at": "2023-06-16T13:52:20.000000Z"
     *  }
     * ],
     *  "first_page_url": "http://devot.test/api/bills?page=1",
     *  "from": 1,
     *  "last_page": 4,
     *  "last_page_url": "http://devot.test/api/bills?page=4",
     *  "links": [
     *   {
     *    "url": null,
     *    "label": "&laquo; Previous",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/bills?page=1",
     *    "label": "1",
     *    "active": true
     *   },
     *   {
     *    "url": "http://devot.test/api/bills?page=2",
     *    "label": "2",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/bills?page=3",
     *    "label": "3",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/bills?page=4",
     *    "label": "4",
     *    "active": false
     *   },
     *   {
     *    "url": "http://devot.test/api/bills?page=2",
     *    "label": "Next &raquo;",
     *    "active": false
     *   }
     *  ],
     *  "next_page_url": "http://devot.test/api/bills?page=2",
     *  "path": "http://devot.test/api/categories",
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
            $categories = $this->billRepository->getAllWithPagination($request);
        } catch (\Exception $e) {
            Log::error('Error while getting all bills with pagination: ' . $e);

            return response()->json('Error while getting all bills with pagination.', 500);
        }

        return response()->json($categories);
    }

    /**
     * Show single bill.
     *
     * @response {
     *    "id": 1,
     *    "user_id": 1,
     *    "amount": "36740.00",
     *    "created_at": null,
     *    "updated_at": "2023-06-16T13:51:34.000000Z"
     * }
     * @authenticated
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
     * Delete a bill.
     *
     * @response {
     *  "message": "Category successfully deleted.",
     * }
     * @authenticated
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
