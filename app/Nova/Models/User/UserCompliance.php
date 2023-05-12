<?php

namespace App\Nova\Models\User;

use App\Nova\Actions\User\UserDocument\ApproveValidation;
use App\Nova\Actions\User\UserDocument\RejectValidation;
use App\Nova\Metrics\User\UserComplianceCountByStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Devpartners\AuditableLog\AuditableLog;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Image;

class UserCompliance extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User\UserCompliance::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Compliance');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Compliance');
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
            BelongsTo::make('Nome', 'user', 'App\Nova\Models\User\User')->searchable()->withSubtitles(),

            Image::make('Frente do documento', 'document_front')->disk('digitalocean')->resolveUsing(function () {
                if ($this->document_front) {
                    return str_replace(config('filesystems.disks.digitalocean.endpoint') . '/' . config('filesystems.disks.digitalocean.bucket') . '/', '', $this->document_front);
                }
            })->onlyOnDetail(),

            Image::make('Verso do documento', 'document_back')->disk('digitalocean')->resolveUsing(function () {
                if ($this->document_back) {
                    return str_replace(config('filesystems.disks.digitalocean.endpoint') . '/' . config('filesystems.disks.digitalocean.bucket') . '/', '', $this->document_back);
                }
            })->onlyOnDetail(),

            Badge::make('Status', 'status_id', function () {
                return $this->status->name;
            })->map([
                'Aprovado' => 'success',
                'Rejeitado' => 'danger',
                'Pendente' => 'warning',
                'Processando' => 'info'
            ]),



            Text::make(__('Motivo da rejeição'), 'message'),

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
            (new UserComplianceCountByStatus(1))->width('1/4'),
            (new UserComplianceCountByStatus(2))->width('1/4'),
            (new UserComplianceCountByStatus(3))->width('1/4'),
            (new UserComplianceCountByStatus(4))->width('1/4'),
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
            new ApproveValidation(\App\Models\User\UserCompliance::get()),
            new RejectValidation(\App\Models\User\UserCompliance::get()),
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
