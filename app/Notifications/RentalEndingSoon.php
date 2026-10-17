<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalEndingSoon extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

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
            ->subject('Напоминание об окончании аренды')
            ->greeting('Здравствуйте!')
            ->line(sprintf(
                'Срок аренды книги «%s» заканчивается %s.',
                $this->order->book->title,
                $this->order->ends_at->format('d.m.Y'),
            ))
            ->line('Если вы хотите продолжить чтение, книгу можно арендовать повторно в каталоге.')
            ->action('Открыть книгу', route('store.show', $this->order->book));
    }
}
