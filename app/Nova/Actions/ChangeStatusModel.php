<?php

namespace App\Nova\Actions;

use App\Models\User\UserStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChangeStatusModel extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Variables.
     */
    protected $model_status;
    public $name;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(mixed $model_status, string $name)
    {
        $this->model_status = $model_status;
        $this->name = $name;
    }

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
            if ($model) {
                $model->sponsor_id = $fields->status;
                $model->save();
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
        return [
            Select::make('Gestor/Assessor', 'status')->options(function () {
                $array = [];
                foreach ($this->model_status as $status) {
                    $array[$status->id] = $status->name;
                }
                return $array;
            })->displayUsingLabels(),
        ];
    }

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
