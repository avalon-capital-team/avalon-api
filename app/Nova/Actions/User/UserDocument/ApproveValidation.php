<?php

namespace App\Nova\Actions\User\UserDocument;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ApproveValidation extends Action
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
            if (in_array($model->status_id, [1])) {
                $model->status_id = 2;
                $model->save();
                if ($model->user->settings) {
                    $model->user->settings->compliance = 1;
                    $model->user->settings->save();
                }
                $this->markAsFinished($model);
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
        return [];
    }
    /**
    * The displayable name of the action.
    *
    * @var string
    */
    public $name = 'Aprovar validação';
}
