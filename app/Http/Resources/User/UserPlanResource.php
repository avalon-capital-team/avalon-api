<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\User\CreatePlanOrderRequest;
use App\Models\User;
use App\Models\User\UserPlan;
use App\Models\Order\Order;
use App\Helpers\FileUploadHelper;
use App\Http\Resources\Credit\CreditResource;
use App\Http\Resources\Plan\PlanResource;

class UserPlanResource
{
    /**
     * Find User Document by userId
     *
     * @param  int $id
     * @return \App\Models\User\UserPlan
     */
    public function findByUserId(int $id)
    {
        return UserPlan::where('user_id', $id)->first();
    }

    /**
     * Create Plan Order
     *
     * @param  \App\Http\Requests\User\UserPlanRequest $request
     * @param  \App\Models\User\UserPlan $plan
     * @param  \App\Models\Order\Order $order
     * @param  \App\Models\Credit\Credit $credit
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function createOrUpdateOrder(User $user, CreatePlanOrderRequest $request)
    {
        $validated = $request->validated();

        $type_id = 1;
        $status_id = 2;
        $description = 'Ordem criada com sucesso';

        $user_plan = $user->plan
            ->where('user_id', $user->id)
            ->first()
            ->update([
                'plan_id' => ($validated['plan_id']),
                'coin_id' => ($validated['coin_id']),
                'amount' => ($validated['amount']),
            ]);
        $order = (new Order())->createOrder($user, $validated);
        $credit = (new CreditResource())->create($user, $validated['coin_id'], $validated['plan_id'], $type_id, $status_id, $validated['amount'], $description, $order->id);
        // $plan = (new PlanResource())->create($user, $user_plan->id, $validated['plan_id'], $validated['coin_id'], $validated['amount'], $validated['income'], $validated['acting'], $validated['payment_voucher_url']);

        if (!$user_plan && !$order && !$credit  && !$plan) {
            throw new \Exception('Não foi possível gerar a orden. Tente novamente mais tarde!');
        }

        return $order;
    }

    /**
     * Store or update documentation
     *
     * @param  \App\Models\User $user
     * @param  void $doc_front
     * @param  void $doc_back
     * @param  void $proof_address
     * @return bool
     * @throws \Exception
     */
    public function upDate(User $user, $data)
    {
        $plan = $this->findByUserId($user->id);

        if ($plan['coin_id'] == 1) {
            $plan->payment_voucher_url = (new FileUploadHelper())->storeFile($data['transfer_voucher'], 'users/vouchers');
        } else if ($plan['coin_id'] == 2) {
            $plan->payment_voucher_url = 'https://www.blockchain.com/explorer/search?search=' . $data['transfer_hash'];
        } else if ($plan['coin_id'] == 3) {
            $plan->payment_voucher_url = 'https://etherscan.io/tx/' . $data['transfer_hash'];
        } else {
            throw new \Exception('Não foi possível enviar seu comprovante!');
        }

        if ($plan->save()) {
            return true;
        }

        throw new \Exception('Não foi possível enviar seu comprovante!');
    }
}
