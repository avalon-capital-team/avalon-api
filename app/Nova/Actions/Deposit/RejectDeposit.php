<?php

namespace App\Nova\Actions\Deposit;

use App\Http\Resources\Deposit\DepositFiatResource;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class RejectDeposit extends Action
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
            if (in_array($model->status_id, [1,2])) {
                try {
                    (new DepositFiatResource())->rejectDeposit($model, $fields->message);
                    $this->markAsFinished($model);
                } catch (\Exception $e) {
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
            Text::make('Motivo da rejeição', 'message')
        ];
    }

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Rejeita depósito';
}
