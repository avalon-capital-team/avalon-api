<?php

namespace App\Nova\Models\Plan;

use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;

class Plan extends Resource
{

    public static $model = \App\Models\Plan\Plan::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Boolean::make('Ativo', 'acting'),

            Currency::make('Valor', 'amount')
                ->displayUsing(function ($value) {
                    return currency_format($value, 'brl');
                })
                ->creationRules('required', 'numeric', 'not_in:0')
                ->updateRules('nullable', 'numeric', 'not_in:0'),

            Currency::make('Rendimento', 'income')
                ->displayUsing(function ($value) {
                    return currency_format($value, 'brl');
                })
                ->creationRules('required', 'numeric', 'not_in:0')
                ->updateRules('nullable', 'numeric', 'not_in:0'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            new \App\Nova\Metrics\Plan\PlanCount(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
