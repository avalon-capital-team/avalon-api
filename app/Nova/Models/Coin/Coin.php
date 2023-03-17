<?php

namespace App\Nova\Models\Coin;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Eminiarts\Tabs\Traits\HasTabs;
use Eminiarts\Tabs\Tabs;
use Laravel\Nova\Fields\Badge;

class Coin extends Resource
{
    use HasTabs;


    public static $permissionsForAbilities = [
        'viewAny' => 'view coins',
        'view' => 'view coins',
        'create' => 'create coins',
        'update' => 'update coins',
        // Token Sale
        'addTokenSale' => 'add coin on tokens sale',
        'attachTokenSale' => 'attach coin on tokens sale',
        'detachTokenSale' => 'detach coin on tokens sale ',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Coin\Coin::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Moedas');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Moeda');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'symbol';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'symbol'
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

            Badge::make('Tipo', 'type')->map([
                'coin' => 'warning',
                'token' => 'info',
                'fiat' => 'success'
            ])
                ->sortable()
                ->rules('required', 'string', 'max:254', 'in:coin,token,fiat'),

            Text::make('Nome', 'name')
                ->sortable()
                ->rules('required', 'string', 'max:254'),

            Text::make('Símbolo', 'symbol')
                ->sortable()
                ->rules('required', 'string', 'max:254'),

            Text::make('Decimais', 'decimals')
                ->sortable()
                ->rules('required', 'string', 'max:254'),

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
        return [];
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
        return true;
    }
    /**
     * Authorize to replicate
     */
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
