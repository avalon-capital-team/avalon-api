<?php

namespace App\Nova\Models\Order;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Devpartners\AuditableLog\AuditableLog;
use App\Nova\Resource;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\MorphOne;

class Order extends Resource
{
    use HasTabs;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order\Order::class;

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
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Vendas');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Venda');
    }

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

            BelongsTo::make('Usuário', 'user', 'App\Nova\Models\User\User')
                ->searchable()
                ->withSubtitles(),

            BelongsTo::make('Moeda', 'coin', 'App\Nova\Models\Coin\Coin')
                ->searchable()
                ->withSubtitles(),

            BelongsTo::make('Forma de pagamento', 'paymentMethod', 'App\Nova\Models\System\PaymentMethod')
                ->searchable()
                ->withSubtitles(),

            Currency::make('Total', 'total')
                ->displayUsing(function ($total) {
                    return currency_format($total, $this->resource->coin->symbol);
                }),

            Badge::make('Status', 'status_id')
                ->map([
                    1 => 'warning',
                    2 => 'info',
                    3 => 'danger',
                    4 => 'danger',
                    5 => 'danger',
                    6 => 'success',
                ])
                ->label(function ($value) {
                    return $this->resource->status->name;
                })
                ->sortable(),

            DateTime::make('Pago em', 'paid_at'),

            // Tabs::make('Relations', [
            //     HasMany::make('Histórico', 'orderHistory', 'App\Nova\Models\Order\OrderHistory'),
            //     HasMany::make('Resumo do Total', 'orderTotal', 'App\Nova\Models\Order\OrderTotal'),
            //     AuditableLog::make(),
            // ]),
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
            new \App\Nova\Metrics\Order\TotalSaleAll(),
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
