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
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Withdrawal\WithdrawalCrypto>
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

            Badge::make('Tipo', 'type', function () {
                return $this->type === 'crypto' ? 'USDT' : 'BTC';
            })->map([
                'BTC' => 'warning',
                'USDT' => 'info'
            ])->hideFromDetail(),

            // Text::make('Chave', 'data->key')->onlyOnDetail(),

            // Text::make('Tipo da chave', 'data->key_type')->onlyOnDetail(),

            // Text::make('Account Type', 'data->type')->onlyOnDetail(),

            // Text::make('Nome do banco', 'data->bank_name')->onlyOnDetail(),

            // Text::make('Código do banco', 'data->bank_code')->onlyOnDetail(),

            // Text::make('Agência', 'data->agency')->onlyOnDetail(),

            // Text::make('Dígito da agencia', 'data->agency_digit')->onlyOnDetail(),

            // Text::make('Conta', 'data->account')->onlyOnDetail(),

            // Badge::make('Status', 'status_id')
            //     ->map([
            //         1 => 'danger',
            //         2 => 'success',
            //         3 => 'warning',
            //     ])
            //     ->label(function ($value) {
            //         return $this->resource->status->name;
            //     })
            //     ->sortable(),


            // DateTime::make('Criado em', 'created_at'),

            // Text::make('Confirmação do pagamento', 'payment_confirmation')
            //     ->onlyOnDetail(),

            // DateTime::make('Aprovado em', 'approved_at')->hideFromIndex(),

            // Text::make('Motivo da rejeição', 'reject_motive')->hideFromIndex(),

            // DateTime::make('Rejeitado em', 'rejected_at')->hideFromIndex(),

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
}
