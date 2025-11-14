<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailWithCode extends Notification implements ShouldQueue
{
    use Queueable;

    protected $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
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
            ->subject('Kode Verifikasi Email LaundryTime')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Terima kasih telah mendaftar di LaundryTime. Berikut adalah kode verifikasi Anda:')
            ->line('**' . $this->code . '**')
            ->line('Kode ini akan kedaluwarsa dalam 10 menit.')
            ->line('Masukkan kode ini di halaman verifikasi untuk menyelesaikan pendaftaran Anda.')
            ->line('Jika Anda tidak membuat akun ini, abaikan email ini.')
            ->salutation('Salam, Tim LaundryTime');
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
