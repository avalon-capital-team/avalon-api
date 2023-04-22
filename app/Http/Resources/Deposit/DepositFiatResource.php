<?php

namespace App\Http\Resources\Deposit;

use App\Helpers\FileUploadHelper;
use App\Models\Deposit\DepositFiat;
use App\Models\User;
use App\Http\Resources\Coin\CoinResource;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Http\Resources\Credit\CreditResource;
use App\Models\Credit\CreditBalance;
use App\Models\System\PaymentMethod\PaymentMethod;
use App\Notifications\Wallet\Deposit\DepositFiatNotification;

class DepositFiatResource
{
    /**
     * Create new Token Sale
     *
     * @param  \App\Models\User $user
     * @param  float $amount
     * @param  string $payment_code
     * @return \App\Models\Deposit\DepositFiat
     * @throws \Exception
     */
    public function create(User $user, float $amount, string $payment_code)
    {
        $coin = (new CoinResource())->findBySymbol('BRL');

        $paymentMethod = PaymentMethod::where('code', $payment_code)->first();
        if (!$paymentMethod) {
            throw new \Exception('Método de pagamento não encontrado.', 406);
        }
        if ($paymentMethod->status == 0) {
            throw new \Exception('Método de pagamento não está disponível no momento.', 403);
        }

        return DepositFiat::create([
            'user_id' => $user->id,
            'coin_id' => $coin->id,
            'payment_method_id' => $paymentMethod->id,
            'amount' => $amount,
            'status_id' => 1
        ]);
    }

    /**
     * Generate payment
     *
     * @param  \App\Models\Deposit\DepositFiat $deposit
     * @return \App\Models\Deposit\DepositFiat
     */
    public function generatePayment(DepositFiat $deposit)
    {
        $method = 'generatePayment' . str_replace(' ', '', ucwords(str_replace('_', ' ', $deposit->paymentMethod->code)));
        return $this->$method($deposit);
    }

    /**
     * Verify the payment of deposit
     *
     * @param  \App\Models\Deposit\DepositFiat $deposit
     * @return \App\Models\Deposit\DepositFiat
     */
    public function verifyPayment(DepositFiat $deposit)
    {
        if ($deposit->status_id == 1) {
            if ($deposit->paymentMethod->code == 'pix') {
                $data = 1;
                if ($data && isset($data['status']) && $data['status'] == 'CONCLUIDA') {
                    # Aprovar pagamento
                    $this->approveDeposit($deposit);
                }
            }
        }

        return $deposit;
    }

    /**
     * Store receipt
     *
     * @param  \App\Models\Deposit\DepositFiat $deposit
     * @param  string $file
     * @return \App\Models\Deposit\DepositFiat
     * @throws \Exception
     */
    public function storeReceipt(User $user, int $deposit_id, string $file)
    {
        $deposit = DepositFiat::where('id', $deposit_id)->where('user_id', $user->id)->first();

        if ($deposit->status_id == 1) {
            $deposit->status_id = 2;
            $deposit->receipt_file = (new FileUploadHelper())->storeFile($file, 'users/deposits');
            $deposit->save();

            return $deposit;
        }

        if ($deposit->status_id == 2) {
            throw new \Exception('O comprovante da intenção de deposito ja foi enviado e esta em analise!', 403);
        }

        throw new \Exception('Não é possível enviar comprovante desta intenção de deposito!', 403);
    }

    /**
     * Get history paginate
     *
     * @param  \App\Models\User $user
     * @return \App\Models\Deposit\DepositFiat;
     */
    public function getHistoryPaginate(User $user)
    {
        return DepositFiat::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(5);
    }

    /**
     * Approve Deposit
     *
     * @param  \App\Models\Deposit\DepositFiat $deposit
     * @return null
     */
    public function approveDeposit(DepositFiat $deposit)
    {
        if (in_array($deposit->status_id, [1, 2])) {
            # Calculate fees
            $feeData =  1.3;

            $description = 'Depósito (' . $deposit->token . ') realizado';
            $amount = $feeData;

            // if ($feeData > 0) {
            //     $description = $description . ' ' . $feeData;
            // }

            # Create Credit
            $credit = (new CreditResource())->create(
                $deposit->user->id,
                $deposit->coin->id,
                0,
                5,
                1,
                floatval($deposit->amount),
                0,
                $description,
            );

            $creditBalance = CreditBalance::where('user_id', $deposit->user->id)->where('coin_id', $deposit->coin->id)->first();
            (new CreditBalanceResource())->moveBalanceToEnable($creditBalance, $deposit->amount);

            # Change status to approved
            $deposit->approved_at = date('Y-m-d H:i:s');
            $deposit->approved_by = (auth()->user()) ? auth()->user()->id : null;
            $deposit->status_id = 4;
            $deposit->save();

            # Store Fee
            // if ($feeData['fee'] > 0) {
            //     (new FeeHistoryResource())->storeFee($feeData, $deposit, $deposit->coin);
            // }

            # Send mail
            // $deposit->user->notify(new DepositFiatNotification($deposit));

        }
    }

    /**
     * Generate payment method bank manual
     *
     * @param \App\Models\Deposit\DepositFiat $deposit
     * @return \App\Models\Deposit\DepositFiat
     */
    public function generatePaymentBankManual(DepositFiat $deposit)
    {
        return $deposit;
    }

    /**
     * Approve Deposit
     *
     * @param  \App\Models\Deposit\DepositFiat $deposit
     * @param  string|null $rejectMotive
     * @return null
     */
    public function rejectDeposit(DepositFiat $deposit, string $rejectMotive = null)
    {
        if (in_array($deposit->status_id, [1, 2])) {
            # Change Status
            $deposit->status_id = 3;
            $deposit->message = $rejectMotive;
            $deposit->rejected_at = date('Y-m-d H:i:s');
            $deposit->save();

            # Send Mail
            // $deposit->user->notify(new DepositFiatNotification($deposit));
        }
    }
}
