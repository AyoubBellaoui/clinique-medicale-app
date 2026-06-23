<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class ClinicNotification extends Notification
{
    public function __construct(
        public readonly string $type,
        public readonly string $message,
        public readonly string $icon,
        public readonly string $color,
        public readonly ?string $url = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => $this->type,
            'message' => $this->message,
            'icon'    => $this->icon,
            'color'   => $this->color,
            'url'     => $this->url,
        ];
    }

    public static function broadcast(string $type, string $message, string $icon, string $color, ?string $url = null): void
    {
        $notification = new static($type, $message, $icon, $color, $url);

        User::all()->each(fn($user) => $user->notify($notification));
    }
}
