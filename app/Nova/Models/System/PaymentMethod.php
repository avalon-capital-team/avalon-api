<?php

namespace App\Nova\Models\System;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Devpartners\AuditableLog\AuditableLog;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Select;
use Eminiarts\Tabs\Traits\HasTabs;

class PaymentMethod extends Resource
{
    use HasTabs;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\System\PaymentMethod\PaymentMethod::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Formas de pagamento');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Forma de pagamento');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [

            Text::make(__('Nome'), 'name')
                ->sortable()
                ->rules('required'),


            Text::make(__('Beneficiário'), 'data->beneficiary'),
            Text::make(__('Banco'), 'data->bank_name'),
            Text::make(__('Documento'), 'data->document'),
            Text::make(__('Agência'), 'data->agency'),
            Text::make(__('Conta'), 'data->account'),


            Badge::make('Tipo', 'type', function () {
                return $this->status === 'external' ? 'Externo' : 'Interno';
            })->map([
                'Externo' => 'warning',
                'Interno' => 'info'
            ]),

            Badge::make('Status', 'status', function () {
                return $this->status === 1 ? 'Ativo' : 'Inativo';
            })->map([
                'Ativo' => 'success',
                'Inativo' => 'danger'
            ])->hideFromDetail(),

            Select::make('Status')->options([
                '0' => 'Inativo',
                '1' => 'Ativo',
            ])->displayUsingLabels()->hideFromIndex(),

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
