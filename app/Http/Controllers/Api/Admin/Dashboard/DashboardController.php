<?php

namespace App\Http\Controllers\Api\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Deposit\DepositFiat;
use App\Models\Plan\Plan;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Withdrawal\WithdrawalFiat;

class DashboardController extends Controller
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
   * @param \App\Htt\Resorces\User\User @resource
   * @return \Illuminate\Http\JsonResponse
   */
  public function data()
  {
    try {

      return response()->json([
        'status'  => true,
        'users' => count(User::get()),
        'clients' => count(User::where('type', 'user')->get()),
        'mananger' => count(User::where('type', 'mananger')->get()),
        'advisor' => count(User::where('type', 'advisor')->get()),
        'total_amount' => Plan::where('acting', 1)->sum('amount'),
        'total_amount_reinvested' => Plan::where('acting', 1)->where('withdrawal_report', true)->sum('amount'),
        'total_amount_off_reinvested' => Plan::where('acting', 1)->where('withdrawal_report', false)->sum('amount'),
        'total_income' => Plan::where('acting', 1)->sum('income'),
        'total_income_reinvested' => Plan::where('acting', 1)->where('withdrawal_report', true)->sum('income'),
        'total_income_off_reinvested' => Plan::where('acting', 1)->where('withdrawal_report', false)->sum('income'),
        'pending_withdral' => WithdrawalFiat::where('status_id', 3)->sum('amount'),
        'awaiting_payment_deposit' => DepositFiat::where('status_id', 1)->sum('amount'),
        'proof_sent_deposit' => DepositFiat::where('status_id', 2)->sum('amount'),
        'list_plans' => (new Plan())->dataUser(),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }
}
