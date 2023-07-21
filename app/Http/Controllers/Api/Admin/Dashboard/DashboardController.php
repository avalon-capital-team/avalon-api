<?php

namespace App\Http\Controllers\Api\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\User\UserResource;
use App\Models\Credit\CreditBalance;
use App\Models\Deposit\DepositFiat;
use App\Models\Plan\Plan;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User\UserPlan;
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
  public function data(UserResource $resource)
  {
    try {
      $extract = (new CreditResource())->sumarySixMonth();
      $total_off = (new CreditBalanceResource())->incomeOffReinvisted();
      $total_amount_reinvested = (new CreditBalanceResource())->amountReinvisted();
      $users_month = (new UserResource())->usersMonth();

      return response()->json([
        'status'  => true,
        'users' => count(User::get()),
        'clients' => count(User::where('type', 'user')->get()),
        'mananger' => count(User::where('type', 'mananger')->get()),
        'advisor' => count(User::where('type', 'advisor')->get()),
        'total_amount' => CreditBalance::where('coin_id', 1)->where('user_id', '!=', 6)->sum('balance_placed'),
        'total_amount_reinvested' => $total_amount_reinvested,
        'total_income_reinvested' => Plan::where('acting', 1)->where('withdrawal_report', true)->where('user_id', '!=', 6)->sum('income'),
        'total_income_off_reinvested' => $total_off,
        'pending_withdral' => WithdrawalFiat::where('status_id', 3)->sum('amount'),
        'awaiting_payment_deposit' => DepositFiat::where('status_id', 1)->sum('amount'),
        'proof_sent_deposit' => DepositFiat::where('status_id', 2)->sum('amount'),
        'users_month' => $users_month,
        'extract' => $extract,
        'list_plans' => $resource->getClients(),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message'  => $e->getMessage()
      ], 400);
    }
  }
}
