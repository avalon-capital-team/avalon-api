<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Publication\PublicationResource;
use App\Models\User;

class UserProfileResource
{
    /**
     * @param  \App\Models\User $user
     *
     * @return array
     */
    public function profileDetail(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->profile->avatar,
                'bio' => $user->profile->bio,
                'is_private' => ($user->profile->is_private) ? true : false,
                'links' => [
                    'facebook' => $user->profile->link_facebook,
                    'instagram' => $user->profile->link_instagram,
                    'twitter' => $user->profile->link_twitter,
                    'tiktok' => $user->profile->link_tiktok,
                ]
            ],
            'totals' => [
                'posts' => (new PublicationResource())->totalPublicationsOfUser($user),
                'followers' => (new UserFollowResource())->totalFollowers($user),
                'following' => (new UserFollowResource())->totalFollowing($user),
            ],
            'wallet' => [
                'address' => null,
                'balance' => 0
            ],
            'notifications' => [
                'unread' => $user->unreadNotifications->count()
            ]
        ];
    }

    /**
     * @param  \App\Models\User $user
     *
     * @return array
     */
    public function profileDetailSimple(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->profile->avatar,
            ],
        ];
    }
}
