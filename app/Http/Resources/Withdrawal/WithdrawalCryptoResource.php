<?php

namespace App\Http\Resources\Withdrawal;

use App\Http\Resources\Coin\CoinResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Credit\CreditResource;
use App\Models\User;
use App\Models\Coin\Coin;
use App\Models\Withdrawal\WithdrawalCrypto;
use App\Notifications\Wallet\Withdrawal\WithdrawalCryptoNotification;

class WithdrawalCryptoResource
{
    /**
     * Create wallet Withdrawal
     *
     * @param  \App\Models\User $user
     * @param  string $type
     * @param  float $amount
     * @param  int $coin_id
     * @return \App\Models\Withdrawal\WithdrawalCrypto
     * @throws \Exception
     */
    public function createWithdrawalCrypto(User $user, int $coin_id, string $type, float $amount)
    {
        (new CoinResource())->coinData();

        # Check Balance
        $balance = (new CreditBalanceResource())->getBalanceByCoinIdAndBalanceId($user, $coin_id);

        if (!$balance) {
            throw new \Exception(__('Carteira não encontrada.'));
        }

        if ($balance->income < $amount) {
            throw new \Exception(__('Você não tem saldo de rendimento suficiente.'));
        }

        if ($balance->balance_enable < $amount) {
            throw new \Exception(__('Você não tem saldo suficiente.'));
        }

        # Description
        if ($coin_id == '3') {
            $description = 'Saque de USDT';
        } else {
            $description = 'Saque de BTC';
        }

        # Create debit
        $debit = (new CreditResource())->create($user->id, $coin_id, $user->userPlan->id, 2, 1, floatval('-' . $amount), $user->userPlan->amount, $description);

        if ($debit) {
            # Create Withdrawal
            $this->withdrawalCrypto($coin_id, $user, $debit, $amount, 3, $type);

            # Update withdrawal field in balance
            (new CreditBalanceResource())->updateField(
                $user,
                $coin_id,
                $amount,
                'withdrawal',
            );

            // # Send mail
            // if (env('APP_ENV') != 'testing') {
            //     $user->notify(
            //         new WithdrawalFiatNotification($withdrawal)
            //     );
            // }

            return true;
        }

        throw new \Exception(__('Não foi possível criar a solicitação de Saque.'));
    }

    public function withdrawalCrypto($coin_id, $user, $debit, $amount, $status_id, $type)
    {
        $withdrawal = WithdrawalCrypto::create([
            'coin_id' => $coin_id,
            'user_id' => $user->id,
            'debit_id' => $debit->id,
            'amount' => $amount,
            'status_id' => $status_id,
            'type' => $type,
            'data' => $user->financial()->where('type', $type)->first()->getData()
        ]);

        return $withdrawal;
    }

    /**
     * Cancel withdrawal & return credit
     *
     * @param  \App\Models\Withdrawal\WithdrawalCrypto $withdrawalFiat
     * @param  string|null $message;
     * @return bool
     * @throws \Exception
     */
    public function cancelWithdrawal(WithdrawalCrypto $withdrawalFiat, string $message = null)
    {
        if ($withdrawalFiat->status_id == 3) {
            if ($message) {
                $description = 'Saque estornado #' . $withdrawalFiat->id . ' (Motivo: ' . $message . ')';
            } else {
                $description = 'Saque estornado #' . $withdrawalFiat->id . '';
            }

            # Create reverse credit
            $credit = (new CreditResource())->create(
                $withdrawalFiat->user,
                $withdrawalFiat->coin,
                3,
                1,
                floatval(str_replace('-', '', $withdrawalFiat->debit->amount)),
                0.000000,
                $description
            );

            if ($credit) {
                # Update Status
                $withdrawalFiat->status_id = 1;
                $withdrawalFiat->reject_motive = $message;
                $withdrawalFiat->save();

                # Update withdrawal field in balance
                (new CreditBalanceResource())->updateField(
                    $withdrawalFiat->user,
                    $withdrawalFiat->coin->id,
                    $withdrawalFiat->debit->amount,
                    'withdrawal'
                );

                # Update withdrawal field in balance
                (new CreditBalanceResource())->updateField(
                    $withdrawalFiat->user,
                    $withdrawalFiat->coin->id,
                    $withdrawalFiat->debit->amount,
                    'received'
                );

                // # Send mail
                // $withdrawalFiat->user->notify(
                //     new WithdrawalCryptoNotification($withdrawalFiat)
                // );

                return true;
            }
        }

        return false;
    }

    /**
     * Cancel withdrawal & return credit
     *
     * @param  \App\Models\Withdrawal\WithdrawalCrypto $withdrawalFiat
     * @param  string|null $payment_confirmation;
     * @return bool
     * @throws \Exception
     */
    public function approveWithdrawal(WithdrawalCrypto $withdrawalFiat, string $payment_confirmation = null)
    {
        if (in_array($withdrawalFiat->status_id, [3])) {
            #Change Status
            $withdrawalFiat->status_id = 2;
            $withdrawalFiat->payment_confirmation = $payment_confirmation;
            $withdrawalFiat->approved_at = date('Y-m-d H:i:s');
            $withdrawalFiat->save();

            # Send mail
            // $withdrawalFiat->user->notify(new WithdrawalCryptoNotification($withdrawalFiat));
        }
    }
    /**
     * Get history paginate
     *
     * @param  \App\Models\User $user
     * @return App\Models\Withdrawal\WithdrawalCrypto;
     */
    public function getHistoryPaginate(User $user)
    {
        return WithdrawalCrypto::where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
    }
}
