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

class WithdrawalFiat extends Resource
{
    use HasTabs;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Withdrawal\WithdrawalFiat::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Saques em Real');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Saque em Real');
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
            ID::make()->sortable(),

            BelongsTo::make('Usuário', 'user', 'App\Nova\Models\User\User')->searchable()->withSubtitles(),

            BelongsTo::make('Moeda', 'coin', 'App\Nova\Models\Coin\Coin')->searchable()->withSubtitles(),

            BelongsTo::make('Débito', 'debit', 'App\Nova\Models\Credit\Credit'),

            Currency::make('Valor', 'amount')
                ->displayUsing(function ($value) {
                    return currency_format($value, $this->resource->coin->symbol);
                }),

            Badge::make('Tipo', 'type', function () {
                return $this->type === 'pix' ? 'Pix' : 'Banco';
            })->map([
                'Banco' => 'warning',
                'Pix' => 'info'
            ])->hideFromDetail(),

            Text::make('Chave', 'data->key')->onlyOnDetail(),

            Text::make('Tipo da chave', 'data->key_type')->onlyOnDetail(),

            Text::make('Account Type', 'data->type')->onlyOnDetail(),

            Text::make('Nome do banco', 'data->bank_name')->onlyOnDetail(),

            Text::make('Código do banco', 'data->bank_code')->onlyOnDetail(),

            Text::make('Agência', 'data->agency')->onlyOnDetail(),

            Text::make('Dígito da agencia', 'data->agency_digit')->onlyOnDetail(),

            Text::make('Conta', 'data->account')->onlyOnDetail(),

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

            Text::make('Confirmação do pagamento', 'payment_confirmation')
                ->onlyOnDetail(),

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
            (new \App\Nova\Metrics\Withdrawal\WithdrawalFiat\WithdrawalFiatApproved())->width('1/4'),
            (new \App\Nova\Metrics\Withdrawal\WithdrawalFiat\WithdrawalFiatPending())->width('1/4'),
            (new \App\Nova\Metrics\Withdrawal\WithdrawalFiat\WithdrawalFiatCancelled())->width('1/4'),
            (new \App\Nova\Metrics\Withdrawal\WithdrawalFiat\WithdrawalFiatPartitionByStatus())->width('1/4'),
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
        return [
            new \App\Nova\Filters\Withdrawal\WithdrawalFiat\WithdrawalFiatFilterByStatus(),
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
        return [
            new ApproveWithdrawal(\App\Models\Withdrawal\WithdrawalFiat::get()),
            new RejectWithdrawal(\App\Models\Withdrawal\WithdrawalFiat::get()),
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
        return true;
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
