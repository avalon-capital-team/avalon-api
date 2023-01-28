<?php

namespace App\Http\Resources\Notifications;

use App\Http\Resources\Publication\PublicationCommentResource;
use App\Http\Resources\Publication\PublicationLikeResource;
use App\Http\Resources\User\UserProfileResource;
use App\Http\Resources\User\UserResource;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationsResource
{
    use PaginationTrait;

    /**
     * @param  \Illuminate\Http\Reques $request
     * @return object
     */
    public function list(Request $request, $limit = 10, $page = 1)
    {
        $notifications = $request->user()->notifications;
        $array = [];

        foreach($notifications as $notification){
            $method = "array".str_replace('_', '', ucwords($notification->data['type'], '_'));
            $array[] = $this->{$method}($notification);
        }

        return $this->paginate($array, $limit, $page);
    }

    /**
     * @param  Illuminate\\Notifications\\DatabaseNotification $notification
     * @return array
     */
    public function arrayPublicationLike(DatabaseNotification $notification)
    {
        $publicationLike = (new PublicationLikeResource())->findById($notification['data']['publication_like_id']);

        $array =  [
            'id' => $notification->id,
            'type' => $notification->data['type'],
            'from_user' => $publicationLike->user,
            'message' => $notification['data']['message'],
            'publication' => $publicationLike->publication->uuid,
            'is_read' => ($notification['read_at']) ? true : false
        ];

        $notification->markAsRead();
        return $array;
    }

    /**
     * @param  Illuminate\\Notifications\\DatabaseNotification $notification
     * @return array
     */
    public function arrayPublicationComment(DatabaseNotification $notification)
    {
        $publicationComment = (new PublicationCommentResource())->findById($notification['data']['publication_comment_id']);

        $array = [
            'id' => $notification->id,
            'type' => $notification->data['type'],
            'from_user' => $publicationComment->user,
            'message' => $notification['data']['message'],
            'publication' => $publicationComment->publication->uuid,
            'is_read' => ($notification['read_at']) ? true : false
        ];

        $notification->markAsRead();
        return $array;
    }
}
