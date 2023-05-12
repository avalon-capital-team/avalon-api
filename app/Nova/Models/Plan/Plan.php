<?php

namespace App\Nova\Models\Plan;

use App\Nova\Actions\User\Voucher\ApprovePaymentVoucher;
use App\Nova\Actions\User\Voucher\RejectPaymentVoucher;
use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Http\Request;


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

            // DateTime::make('Aprovado em', 'activated_at')->hideFromIndex(),

            BelongsTo::make('Moeda', 'coin', 'App\Nova\Models\Coin\Coin'),

            BelongsTo::make('UserPlan', 'user', 'App\Nova\Models\User\User'),

            BelongsTo::make('Plano', 'dataPlan', 'App\Nova\Models\Data\DataPlan'),

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
                ->creationRules('nullable', 'numeric')
                ->updateRules('nullable', 'numeric'),

            Image::make('Comprovante de deposito', 'payment_voucher_url')->disk('digitalocean')->resolveUsing(function () {
                if ($this->payment_voucher_url) {
                    return str_replace(config('filesystems.disks.digitalocean.endpoint') . '/' . config('filesystems.disks.digitalocean.bucket') . '/', '', $this->payment_voucher_url);
                }
            })->onlyOnDetail(),
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
        return [
            new ApprovePaymentVoucher(\App\Models\Plan\Plan::get()),
            new RejectPaymentVoucher(\App\Models\Plan\Plan::get()),
        ];
    }

    /**
     * Authorize to create
     */
    public static function authorizedToCreate(Request $request)
    {
        return true;
    }
    /**
     * Authorize to delete
     */
    public function authorizedToDelete(Request $request)
    {
        return true;
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
