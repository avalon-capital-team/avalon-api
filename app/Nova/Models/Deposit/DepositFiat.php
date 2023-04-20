<?php

namespace App\Nova\Models\Deposit;

use App\Nova\Actions\Deposit\ApproveDeposit;
use App\Nova\Actions\Deposit\RejectDeposit;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Devpartners\AuditableLog\AuditableLog;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Image;
use Eminiarts\Tabs\Traits\HasTabs;
use Laravel\Nova\Fields\Badge;

class DepositFiat extends Resource
{
    use HasTabs;
    // /*
    //  * Permissions & Roles
    //  */
    // use \Itsmejoshua\Novaspatiepermissions\PermissionsBasedAuthTrait;

    // public static $permissionsForAbilities = [
    //     'viewAny' => 'view deposits fiat',
    //     'view' => 'view deposits fiat',
    // ];


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Deposit\DepositFiat::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Depósitos em Real');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Depósito em Real');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'token';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'token'
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
            Text::make('Token', 'token'),

            BelongsTo::make('Usuário', 'user', 'App\Nova\Models\User\User')->searchable()->withSubtitles(),

            BelongsTo::make('Moeda', 'coin', 'App\Nova\Models\Coin\Coin')->searchable()->withSubtitles(),

            BelongsTo::make('Forma de pagamento', 'paymentMethod', 'App\Nova\Models\System\PaymentMethod')->searchable()->withSubtitles(),

            Badge::make('Status', 'status_id')
                ->map([
                    1 => 'warning',
                    2 => 'info',
                    3 => 'danger',
                    4 => 'success',
                ])
                ->label(function ($value) {
                    return $this->resource->status->name;
                })
                ->sortable(),
            Currency::make('Valor', 'amount')
                ->displayUsing(function ($value) {
                    return currency_format($value, $this->resource->coin->symbol);
                }),

            Image::make('Comprovante de deposito', 'receipt_file')->disk('digitalocean')->resolveUsing(function () {
                if ($this->receipt_file) {
                    return str_replace(config('filesystems.disks.digitalocean.endpoint') . '/' . config('filesystems.disks.digitalocean.bucket') . '/', '', $this->receipt_file);
                }
            }),

            DateTime::make('Criado em', 'created_at'),

            DateTime::make('Aprovado em', 'approved_at')->hideFromIndex(),

            Text::make('Motivo da rejeição', 'message')->hideFromIndex(),

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
            new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatWaitingPayment(),
            new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatReceiptSent(),
            new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatApproved(),
            new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatRejected(),
            new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatTotal(),
            new \App\Nova\Metrics\Deposit\DepositFiat\DepositFiatPartitionByStatus(),
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
            new \App\Nova\Filters\Deposit\DepositFiat\DepositFiatFilterByStatus(),
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
            new ApproveDeposit(\App\Models\Deposit\DepositFiat::get()),
            new RejectDeposit(\App\Models\Deposit\DepositFiat::get()),
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
