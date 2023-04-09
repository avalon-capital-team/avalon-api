<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use HasTabs;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        # Menu
        Nova::mainMenu(function (Request $request) {

            return [
                MenuSection::dashboard(\App\Nova\Dashboards\Main::class)->icon('chart-bar'),
                MenuSection::make('Usuários', [
                    MenuItem::resource(\App\Nova\Models\User\User::class),
                    MenuItem::resource(\App\Nova\Models\User\UserCompliance::class),
                    MenuItem::resource(\App\Nova\Models\User\UserPlan::class),
                ])->icon('user-group')->collapsable(),

                MenuSection::make('Saques', [
                    MenuItem::resource(\App\Nova\Models\Withdrawal\WithdrawalFiat::class),
                ])->icon('upload')->collapsable(),

                MenuSection::make('Extratos', [
                    MenuItem::resource(\App\Nova\Models\Credit\Credit::class),
                ])->icon('cash')->collapsable(),

                MenuSection::make('Planos', [
                    MenuItem::resource(\App\Nova\Models\Data\DataPlan::class),
                ])->icon('briefcase')->collapsable(),

                MenuSection::make('Porcentagem', [
                    MenuItem::resource(\App\Nova\Models\Data\DataPercent::class),
                ])->icon('variable')->collapsable(),

                MenuSection::make('Moedas', [
                    MenuItem::resource(\App\Nova\Models\Coin\Coin::class),
                ])->icon('currency-dollar')->collapsable(),

            ];
        });

        // Footer
        Nova::footer(function ($request) {
            return Blade::render('
                <div class="mt-8 leading-normal text-xs text-gray-500 space-y-1">
                    <p class="text-center">Desenvolvido por Avalon</p>
                    <p class="text-center">© 2022 Avalon.com</p>
                </div>
            ');
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
