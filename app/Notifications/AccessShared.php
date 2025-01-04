<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccessShared extends Notification {
    use Queueable;

    private $link;
    private $access;
    private $workspace;
    /**
     * Create a new notification instance.
     */
    public function __construct( $workspace, $access, $link = null ) {
        $this->access = $access;
        $this->link = $link ?? url('/');
        $this->workspace = $workspace;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->line( 'You\'ve given (' . $this->access . ') access to "' . $this->workspace . '"' )
            ->line( 'Click the button below to access your new workspace. Link will expire in 1 hour' )
            ->action('Access Now', $this->link )
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array {
        return [
            //
        ];
    }
}
