<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\User\CreatePlanOrderRequest;
use App\Models\User;
use App\Models\User\UserPlan;
use App\Models\Order\Order;
use App\Helpers\FileUploadHelper;

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
    public function createPlanOrder(User $user, CreatePlanOrderRequest $request)
    {
        $validated = $request->validated();

        $plan = (new UserPlan())->createPlan($user, $validated);
        $order = (new Order())->createOrder($user, $validated);

        if (!$plan && !$order) {
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
            $plan->payment_voucher_url = (new FileUploadHelper())->storeFile($data['transfer_voucher'], 'users/documents');
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
