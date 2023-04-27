<?php

namespace App\Http\Resources\Withdrawal;

use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Credit\CreditResource;
use App\Models\Coin\Coin;
use App\Models\User;
use App\Models\Withdrawal\WithdrawalFiat;
use App\Notifications\Wallet\Withdrawal\WithdrawalFiatNotification;

class WithdrawalFiatResource
{
    /**
     * Create wallet Withdrawal
     *
     * @param  \App\Models\User $user
     * @param  string $type
     * @param  float $amount
     * @param  int $coin_id
     * @return \App\Models\Withdrawal\WithdrawalFiat
     * @throws \Exception
     */
    public function createWithdrawal(User $user, int $coin_id, string $type, float $amount)
    {
        # Check Balance
        $balance = (new CreditBalanceResource())->getBalanceByCoinIdAndBalanceId($user, $coin_id);

        if (!$balance) {
            throw new \Exception('Carteira não encontrada.', 403);
        }

        if ($balance->balance_enable < $amount) {
            throw new \Exception('Você não tem saldo suficiente.', 403);
        }

        # Description
        if ($type == 'bank') {
            $description = 'Saque para a Conta Bancária';
        } else {
            $description = 'Saque para o PIX';
        }

        # Create debit
        $debit = (new CreditResource())->create($user->id, $coin_id, $user->userPlan->id, 2, 1, floatval('-' . $amount), $user->userPlan->amount, $description);

        $coin = Coin::where('coin_id', $coin_id)->first();
        dd($coin);
        $balance = (new CreditBalanceResource())->checkBalanceByCoinId($user, $coin);
        (new CreditBalanceResource())->moveBalanceToEnable($balance, floatval('-' . $amount));

        if ($debit) {
            # Create Withdrawal
            $this->withdrawalFiat($coin_id, $user, $debit, $amount, 3, $type);

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

        throw new \Exception('Não foi possível criar a solicitação de Saque.', 403);
    }

    public function withdrawalFiat($coin_id, $user, $debit, $amount, $status_id, $type)
    {
        return WithdrawalFiat::create([
            'coin_id' => $coin_id,
            'user_id' => $user->id,
            'debit_id' => $debit->id,
            'amount' => $amount,
            'status_id' => $status_id,
            'type' => $type,
            'data' => $user->financial()->where('type', $type)->first()->getData()
        ]);
    }

    /**
     * Cancel withdrawal & return credit
     *
     * @param  \App\Models\Withdrawal\WithdrawalFiat $withdrawalFiat
     * @param  string|null $message;
     * @return bool
     * @throws \Exception
     */
    public function cancelWithdrawal(WithdrawalFiat $withdrawalFiat, string $message = null)
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

                # Send mail
                $withdrawalFiat->user->notify(
                    new WithdrawalFiatNotification($withdrawalFiat)
                );

                return true;
            }
        }

        return false;
    }

    /**
     * Cancel withdrawal & return credit
     *
     * @param  \App\Models\Withdrawal\WithdrawalFiat $withdrawalFiat
     * @param  string|null $payment_confirmation;
     * @return bool
     * @throws \Exception
     */
    public function approveWithdrawal(WithdrawalFiat $withdrawalFiat, string $payment_confirmation = null)
    {
        if (in_array($withdrawalFiat->status_id, [3])) {
            #Change Status
            $withdrawalFiat->status_id = 2;
            $withdrawalFiat->payment_confirmation = $payment_confirmation;
            $withdrawalFiat->approved_at = date('Y-m-d H:i:s');
            $withdrawalFiat->save();

            # Send mail
            // $withdrawalFiat->user->notify(new WithdrawalFiatNotification($withdrawalFiat));
        }
    }
    /**
     * Get history paginate
     *
     * @param  \App\Models\User $user
     * @return App\Models\Withdrawal\WithdrawalFiat;
     */
    public function getHistoryPaginate(User $user)
    {
        return WithdrawalFiat::where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
    }
}
