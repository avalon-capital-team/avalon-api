<?php

namespace App\Http\Resources\System\Rules;

use App\Models\System\Rules\LimitOfUser;
use App\Models\User;
use App\Models\Deposit\DepositFiat;
use App\Models\Withdrawal\WithdrawalCrypto;
use App\Models\Withdrawal\WithdrawalFiat;

class LimitOfUserResource
{
  /**
   * @param  \App\Models\User $user;
   * @return array
   */
  public function checkLimits(User $user)
  {
    $limitsSystem = LimitOfUser::first();

    if ($user->compliance) {
      # User validated
      if ($user->userPlan->coin_id == 1) {
        $data['withdrawal'] = $limitsSystem->withdrawal_fiat_user_validated - WithdrawalFiat::where('status_id', '!=', 1)->where('user_id', $user->id)->where('created_at', 'LIKE', date('Y-m') . '%')->sum('amount');
      }
      $data['withdrawal'] = $limitsSystem->withdrawal_fiat_user_validated - WithdrawalCrypto::where('status_id', '!=', 1)->where('user_id', $user->id)->where('created_at', 'LIKE', date('Y-m') . '%')->sum('amount');
    } else {
      # User not validated
      if ($user->userPlan->coin_id == 1) {
        $data['withdrawal'] = $limitsSystem->withdrawal_fiat_user_validated - WithdrawalFiat::where('status_id', '!=', 1)->where('user_id', $user->id)->where('created_at', 'LIKE', date('Y-m') . '%')->sum('amount');
      }
      $data['withdrawal'] = $limitsSystem->withdrawal_fiat_user_not_validated - WithdrawalCrypto::where('status_id', '!=', 1)->where('user_id', $user->id)->where('created_at', 'LIKE', date('Y-m') . '%')->sum('amount');
    }

    if ($data['withdrawal'] < 0) {
      $data['withdrawal'] = 0;
    }

    return $data;
  }
}
