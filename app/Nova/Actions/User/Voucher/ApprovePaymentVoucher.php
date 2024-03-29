<?php

namespace App\Nova\Actions\User\Voucher;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Carbon\Carbon;
use App\Http\Resources\Credit\CreditBalanceResource;
use App\Models\User\UserPlan;

class ApprovePaymentVoucher extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $model->acting = 1;
            (new CreditBalanceResource())->approveBalance($model);
            (new UserPlan())->activatedAt($model->user_plan_id);
            $model->activated_at = Carbon::now();
            $model->save();
            $this->markAsFinished($model);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Ativar Plano';
}
