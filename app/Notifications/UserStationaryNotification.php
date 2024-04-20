<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserStationaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $user;
    private $stationary_number;
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $stationary_number)
    {
        $this->user = $user;
        $this->stationary_number = $stationary_number;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting('Pengajuan Barang')
                    ->line($this->user->name.' ('.$this->user->email.') telah mengajukan pangadaan barang, mohon di tinjau ulang di halaman berikut')
                    ->action('Lihat Pengajuan', route('stationary.show', $this->stationary_number));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
