<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InventoryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->action == 're_sotck') {
            return [
                'data' => ' Your inventory has been restocked successfully'
            ];
        }
        if ($this->action == "empty") {
            return [
                'data' => ' Your inventory is out of stock'
            ];
        }

        return [ $this->action ];
    }
}
