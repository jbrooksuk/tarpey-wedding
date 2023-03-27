<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

class WeddingUpdate extends Notification
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['nexmo'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
                    ->content($this->message($notifiable));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Replace variables in a message.
     *
     * @param mixed $notifiable
     *
     * @return string
     */
    protected function message($notifiable) : string
    {
        $message = $this->message;

        $message = str_replace('{{family}}', $notifiable->name, $message);
        $message = str_replace('{{ family }}', $notifiable->name, $message);
        $message = str_replace('{{name}}', $notifiable->name, $message);
        $message = str_replace('{{ name }}', $notifiable->name, $message);

        $message = str_replace('{{code}}', $notifiable->invite->code, $message);
        $message = str_replace('{{ code }}', $notifiable->invite->code, $message);
        $message = str_replace('{{invite}}', $notifiable->invite->code, $message);
        $message = str_replace('{{ invite }}', $notifiable->invite->code, $message);

        $message = str_replace('{{cost}}', $notifiable->cost, $message);
        $message = str_replace('{{ cost }}', $notifiable->cost, $message);

        return $message;
    }
}
