<?php

namespace App\Http\Controllers\Api\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\TransferVoucherRequest;
use App\Http\Resources\User\UserPlanResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Models\Order\Order;
use App\Http\Requests\User\CreatePlanOrderRequest;
use Aws\S3\Transfer;
use Illuminate\Support\Facades\DB;

class UserPlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @param  \App\Http\Resources\User\UserPlanResource $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPlan()
    {
        try {
            $plan = (new UserPlanResource())->findByUserId(auth()->user()->id);
            // dd($plan);
            return response()->json([
                'status' => true,
                'plan' => $plan,
                'balace' => (new CreditBalanceResource())->getBalancesByCoinId(auth()->user(), $plan->coin_id),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * Create Order
     * @param \App\Htt\Resorces\User\UserPlanResource @resource
     * @param \App\Htt\Request\User\CreatePlanOrderRequest @request
     * @return \Illiminate\Http\Json
     */
    public function createOrder(UserPlanResource $resource, CreatePlanOrderRequest $request)
    {
        try {
            if ($resource->createPlanOrder(auth()->user(), $request)) {
                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'A orden foi criada com sucesso',
                    'order' => (new Order())->findByOrderUserId(auth()->user()->id),
                    'plan' => (new UserPlanResource())->findByUserId(auth()->user()->id),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }

    /**
     * @param \App\Htt\Resorces\User\UserPlanResource @resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadeVoucher(UserPlanResource $resource, TransferVoucherRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($resource->upDate(auth()->user(), $validated)) {

                return response()->json([
                    'status'  => true,
                    'message' => __('Comprovante enviado com sucesso'),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'  => $e->getMessage()
            ], 400);
        }
    }
}
