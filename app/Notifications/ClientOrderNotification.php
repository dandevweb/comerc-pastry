<?php

namespace App\Notifications;

use App\Models\{Client, Order};
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ClientOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(Client $notifiable): MailMessage
    {
        $order = $this->order;

        $message = (new MailMessage())
            ->subject('Comerc Pastry - Novo pedido')
            ->greeting('Olá, '.$notifiable->name)
            ->line('Você tem um novo pedido na nossa loja.')
            ->line('Detalhes do pedido: ');
        ;

        foreach ($order->products as $product) {
            $message->line($product->name.' - '.$product->price.' reais');
        }
        $message->line('Total: R$'.$this->order->total);

        return $message;
    }
}
