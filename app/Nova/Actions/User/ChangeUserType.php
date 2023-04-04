<?php

namespace App\Nova\Actions\User;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChangeUserType extends Action
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
            try {
                $model->type = $fields->type;
                $model->save();
                $this->markAsFinished($model);
            } catch (\Exception $e) {
                $this->markAsFailed($model, $e);
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
            Select::make('Tipo', 'type')->options([
                'user' => 'Cliente',
                'mananger' => 'Gestor',
                'advisor' => 'Acessor'
            ]),
        ];
    }

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Alterar tipo do usuário';
}
