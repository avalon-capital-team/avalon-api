<?php

namespace App\Nova\Models\User;

use App\Nova\Actions\ChangeStatusModel;
use App\Nova\Actions\User\ChangeUserType;
use App\Nova\Metrics\CountModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Resource;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;

class User extends Resource
{
    use HasTabs;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;


    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Usuários');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Usuário');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->username;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'document', 'email', 'username'
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

            Badge::make('Tipo', 'type', function () {
                if ($this->type == 'user') {
                    return 'Usuário';
                } else if ($this->type == 'mananger') {
                    return 'Gestor';
                } else  if ($this->type == 'advisor') {
                    return 'Assessor';
                } else {
                    return 'Admin';
                }
            })->map([
                'Usuário' => 'success',
                'Gestor' => 'warning',
                'Assessor' => 'info',
                'Admin' => 'danger'
            ]),

            Text::make('Nome', 'name')
                ->sortable()
                ->rules('required', 'string', 'max:255'),

            Text::make('Nome de usuário', 'username')
                ->sortable()
                ->rules('required', 'max:255'),


            Select::make('Tipo do documento', 'document_type')->options([
                'CPF' => 'CPF',
                'CNPJ' => 'CNPJ',
            ])->displayUsingLabels()->hideFromIndex(),

            Text::make(__('Documento'), 'document')
                ->sortable()
                ->rules('required', 'max:255')->hideFromIndex(),

            Text::make('Telefone', 'phone')
                ->sortable()
                ->rules('required', 'max:254'),

            Text::make('Id do indicador', 'sponsor_id')
                ->sortable(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),


            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),

            Tabs::make('Relations', [
                HasOne::make('Plano', 'userPlan', 'App\Nova\Models\User\UserPlan')
                    ->exceptOnForms()
                    ->hideFromDetail(function () {
                        return $this->type == 'admin';
                    }),

                HasOne::make('Endereço', 'address', 'App\Nova\Models\User\UserAddress')
                    ->exceptOnForms()
                    ->hideFromDetail(function () {
                        return $this->type == 'admin';
                    }),
            ]),
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
            (new CountModel(\App\Models\User::where('type', 'user'), 'Total de usuários'))->width('1/3')->icon('user-group'),
            (new CountModel(\App\Models\User::where('type', 'mananger'), 'Total de gestores'))->width('1/3')->icon('user-group'),
            (new CountModel(\App\Models\User::where('type', 'advisor'), 'Total de assessores'))->width('1/3')->icon('user-group'),
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
            new ChangeStatusModel(\App\Models\User::get(), 'Setar um Gestor ou Assessor'),
            new ChangeUserType(\App\Models\User\UserStatus::get(), 'Alterar tipo do usuário'),

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
