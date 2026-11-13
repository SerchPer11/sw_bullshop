<?php

namespace App\Mail;

use App\Models\Bussiness\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderDeliveredMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address('bullshop.mex@gmail.com', 'Soporte BullShop'),
            ],
            subject: "¡Tu pedido {$this->order->order_number} ha sido entregado! | BullShop",
        );
    }

    public function content(): Content
    {
        $customerUrl = URL::temporarySignedRoute(
            'order.customer.show',
            now()->addDays(30),
            ['orderNumber' => $this->order->order_number],
        );

        $customerName = e($this->order->customer_name);
        $orderNumber = e($this->order->order_number);

        return new Content(
            view: 'emails.welcome',
            with: [
                'body' => "
                    <h2><strong>¡Pedido Entregado, {$customerName}!</strong></h2>
                    <p>¡Tu pedido <strong>{$orderNumber}</strong> te ha sido entregado en el evento! Esperamos que tu bulldog disfrute su nuevo gear. Gracias por confiar en BullShop.</p>
                    <hr style=\"border:0;border-top:1px solid #e2e8f0;margin:20px 0;\">
                    <p>
                        <strong>Orden:</strong> {$orderNumber}<br>
                        <strong>Estado del pedido:</strong> Completado / Entregado
                    </p>
                ",
                'buttonData' => [
                    'url' => $customerUrl,
                    'text' => 'Ver resumen de mi pedido',
                ],
                'footer' => '<p>Etiquétanos en Instagram estrenando tu nuevo gear con tu gordo.</p><p>Magno Chabelo &amp; El equipo BullShop</p>',
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
