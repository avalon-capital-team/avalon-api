<?php

namespace App\Http\Controllers\Api\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\TransferVoucherRequest;
use App\Http\Resources\User\UserPlanResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Coin\CoinResource;
use App\Http\Requests\User\CreatePlanOrderRequest;
use App\Http\Resources\Plan\PlanResource;
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
    public function getUserPlans()
    {
        try {
            $plans = (new PlanResource())->listPlans(auth()->user()->id);

            return response()->json([
                'status' => true,
                'plans' => $plans,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], $e->getCode() ?? 400);
        }
    }


    /**
     * @param  \App\Http\Resources\User\UserPlanResource $resource
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPlan()
    {
        try {
            $plan = (new UserPlanResource())->findByUserId(auth()->user()->id);

            if ($plan->user_id == 6) dd($plan->acting = 1);
            if ($plan->coin_id != null) {
                $balance = (new CreditBalanceResource())->getBalancesByCoinId(auth()->user(), $plan->coin_id);
            } else {
                $balance = [
                    'balance_enable' => '0.000000',
                    'balance_placed' => '0.000000',
                    'balance_pending' => '0.000000',
                    'balance_canceled' => '0.000000',
                    'deposited' => '0.000000',
                    'used' => '0.000000',
                    'withdrawal' => '0.000000',
                    'received' => '0.000000',
                    'income' => '0.000000'
                ];
            }

            return response()->json([
                'status' => true,
                'plan' => $plan,
                'balace' => $balance,
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
    public function createOrUpdate(UserPlanResource $resource, CreatePlanOrderRequest $request)
    {
        try {
            $plan = $resource->createOrUpdateOrder(auth()->user(), $request);
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'A orden foi criada com sucesso',
                'plan' => $plan,
            ]);
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


    public function rentabil()
    {
        try {
            return response()->json([
                'status'  => true,
                'plan' => (new PlanResource())->checkIfNeedPayToday(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'  => $e->getMessage()
            ], 400);
        }
    }
}
