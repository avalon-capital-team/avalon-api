<?php

namespace App\Nova\Dashboards;

use App\Models\User;
use App\Nova\Metrics\CountModel;
use App\Nova\Metrics\CountModelByDays;
use App\Nova\Metrics\User\UserCount;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{

    /**
     * Get the displayable name of the metric
     *
     * @return string
     */
    public function name()
    {
        return 'Dashboard';
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            (new CountModelByDays(\App\Models\User::where('type', 'user'), 'Usuários por dia'))->width('1/2'),
            (new CountModel(\App\Models\User::where('type', 'user'), 'Total de usuários'))->width('1/3')->icon('user-group'),
            (new CountModel(\App\Models\User::where('type', 'user')->where('genre_id', 1), 'Total de usuários Masculinos'))->width('1/3')->icon('users'),
            (new CountModel(\App\Models\User::where('type', 'user')->where('genre_id', 2), 'Total de usuários Femininos'))->width('1/3')->icon('users'),
            (new CountModel(\App\Models\User\UserFollow::class, 'Total de seguidores'))->width('1/3')->icon('badge-check'),
            (new CountModel(\App\Models\Publication\Publication::class, 'Total de publicações'))->width('1/3')->icon('photograph'),
            (new CountModel(\App\Models\Publication\PublicationReport::class, 'Total de denuncias'))->width('1/3')->icon('exclamation-circle'),
            (new CountModel(\App\Models\Publication\PublicationLike::class, 'Total de curtidas'))->width('1/3')->icon('thumb-up'),
            (new CountModel(\App\Models\Publication\PublicationComment::class, 'Total de comentários'))->width('1/3')->icon('chat'),
            (new CountModel(\App\Models\Publication\PublicationCommentLike::class, 'Total de comentários curtidos'))->width('1/3')->icon('thumb-up'),

        ];
    }
}
