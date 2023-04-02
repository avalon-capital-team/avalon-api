<?php

namespace App\Nova\Actions\Withdrawal;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Http\Resources\Withdrawal\WithdrawalFiatResource;

class RejectWithdrawal extends Action
{
    use InteractsWithQueue;
    use Queueable;
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
            if (in_array($model->status_id, [3])) {
                try {
                    (new WithdrawalFiatResource())->cancelWithdrawal($model, $fields->message);
                    $this->markAsFinished($model);
                } catch (Exception $e) {
                    $this->markAsFailed($model, $e);
                }
            }
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
        return [
            Text::make('Motivo do estorno', 'message')
        ];
    }

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Estornar saque';
}
