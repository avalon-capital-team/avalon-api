<?php

namespace App\Nova\Models\Withdrawal;

use App\Nova\Actions\Withdrawal\ApproveWithdrawal;
use App\Nova\Actions\Withdrawal\RejectWithdrawal;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Devpartners\AuditableLog\AuditableLog;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Eminiarts\Tabs\Traits\HasTabs;

class WithdrawalCrypto extends Resource
{
    use HasTabs;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Withdrawal\WithdrawalCrypto::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Saques em Crypto');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Saque em Crypto');
    }

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
        'id'
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
            BelongsTo::make('Usuário', 'user', 'App\Nova\Models\User\User')->searchable()->withSubtitles(),

            BelongsTo::make('Moeda', 'coin', 'App\Nova\Models\Coin\Coin')->searchable()->withSubtitles(),

            BelongsTo::make('Débito', 'debit', 'App\Nova\Models\Credit\Credit'),

            Currency::make('Valor', 'amount')
                ->displayUsing(function ($value) {
                    return currency_format($value, $this->resource->coin->symbol);
                }),

            Text::make('Endereço da Wallet', 'data->address')->onlyOnDetail(),

            Text::make('Rede da Wallet', 'data->network')->onlyOnDetail(),

            Badge::make('Status', 'status_id')
                ->map([
                    1 => 'danger',
                    2 => 'success',
                    3 => 'warning',
                ])
                ->label(function ($value) {
                    return $this->resource->status->name;
                })
                ->sortable(),

            DateTime::make('Criado em', 'created_at'),

            DateTime::make('Aprovado em', 'approved_at')->hideFromIndex(),

            Text::make('Motivo da rejeição', 'reject_motive')->hideFromIndex(),

            DateTime::make('Rejeitado em', 'rejected_at')->hideFromIndex(),

            AuditableLog::make(),

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
            (new \App\Nova\Metrics\Withdrawal\WithdrawalCrypto\WithdrawalCryptoApproved())->width('1/4'),
            (new \App\Nova\Metrics\Withdrawal\WithdrawalCrypto\WithdrawalCryptoPending())->width('1/4'),
            (new \App\Nova\Metrics\Withdrawal\WithdrawalCrypto\WithdrawalCryptoCancelled())->width('1/4'),
            (new \App\Nova\Metrics\Withdrawal\WithdrawalCrypto\WithdrawalCryptoPartitionByStatus())->width('1/4'),
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
        return [
            new ApproveWithdrawal(\App\Models\Withdrawal\WithdrawalCrypto::get()),
            new RejectWithdrawal(\App\Models\Withdrawal\WithdrawalCrypto::get()),
        ];
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
