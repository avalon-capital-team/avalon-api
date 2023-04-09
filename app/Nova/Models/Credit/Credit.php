<?php

namespace App\Nova\Models\Credit;

use App\Models\Coin\Coin;
use App\Nova\Filters\Credit\CreditFilterByCoin;
use App\Nova\Filters\Credit\CreditFilterByType;
use App\Nova\Metrics\Credit\CreditBalance;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Devpartners\AuditableLog\AuditableLog;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\ID;
use Eminiarts\Tabs\Traits\HasTabs;

class Credit extends Resource
{
    use HasTabs;
    /*
     * Permissions & Roles
     */

    public static $permissionsForAbilities = [
        'viewAny' => 'view credits',
        'view' => 'view credits',
        'create' => 'create credits',
        'update' => 'update credits',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Credit\Credit::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Extrato geral');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Extrato geral');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'description';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'description'
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

            BelongsTo::make('Usuário', 'user', 'App\Nova\Models\User\User')->searchable()->withSubtitles(),

            BelongsTo::make('Moeda', 'coin', 'App\Nova\Models\Coin\Coin'),

            Currency::make('Valor', 'amount')
                ->displayUsing(function ($value) {
                    return currency_format($value, $this->resource->coin->symbol);
                })
                ->creationRules('required', 'numeric', 'not_in:0')
                ->updateRules('nullable', 'numeric', 'not_in:0'),

            Currency::make('Valor', 'amount')
                ->displayUsing(function ($value) {
                    return currency_format($value, $this->resource->coin->symbol);
                })
                ->creationRules('required', 'numeric', 'not_in:0')
                ->updateRules('nullable', 'numeric', 'not_in:0'),

            Currency::make('Valor', 'amount')
                ->displayUsing(function ($value) {
                    return currency_format($value, $this->resource->coin->symbol);
                })
                ->creationRules('required', 'numeric', 'not_in:0')
                ->updateRules('nullable', 'numeric', 'not_in:0'),

            Text::make('Description')
                ->creationRules('required', 'string', 'max:250')
                ->updateRules('nullable', 'string', 'max:250'),

            Stack::make('Tipo', [
                Text::make('Type Id')->resolveUsing(function () {
                    return optional($this->resource->type)->name;
                }),
            ]),

            DateTime::make('Criado em', 'created_at'),

            // AuditableLog::make(),

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
        $coins = Coin::where('show_wallet', 1)->get();
        $array = [];
        foreach ($coins as $coin) {
            $array[] = (new CreditBalance($coin->id));
        }
        return $array;
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            // (new CreditFilterByType()),
            // (new CreditFilterByCoin()),
        ];
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
    /**
     * Authorize to create
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    /**
     * Authorize to delete
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    /**
     * Authorize to delete
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
    /**
     * Authorize to replicate
     */
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
