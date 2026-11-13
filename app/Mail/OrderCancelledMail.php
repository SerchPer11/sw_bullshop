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

class OrderCancelledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        $isCash = (int) $this->order->payment_method === 1;

        return new Envelope(
            replyTo: [
                new Address('bullshop.mex@gmail.com', 'Soporte BullShop'),
            ],
            subject: $isCash
                ? "Cancelación de tu pedido {$this->order->order_number} en BullShop"
                : "Cancelación y reembolso de tu pedido {$this->order->order_number} en BullShop",
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
        $totalAmount = number_format((float) $this->order->total_amount, 2);
        $isCash = (int) $this->order->payment_method === 1;
        $heading = $isCash ? 'Pedido Cancelado' : 'Pedido Cancelado y Reembolsado';
        $paymentMessage = $isCash
            ? 'Tu pedido ha sido cancelado. Como elegiste pagar en efectivo, no se procesó ningún cargo.'
            : 'Tu pedido ha sido cancelado y el reembolso en tu tarjeta ha sido procesado. El dinero se reflejará en tu cuenta en los próximos días dependiendo de tu banco.';
        $paymentStatus = $isCash ? 'Cancelado' : 'Reembolsado';
        $refundMessage = $isCash
            ? ''
            : "<strong>Monto reembolsado:</strong> \${$totalAmount} MXN";
        $footer = $isCash
            ? '<p>Si tienes alguna duda sobre la cancelación, responde a este correo y con gusto te ayudaremos.</p><p>Magno Chabelo &amp; El equipo BullShop</p>'
            : '<p>Si tienes alguna duda sobre tu reembolso, responde a este correo y con gusto te ayudaremos.</p><p>Magno Chabelo &amp; El equipo BullShop</p>';

        return new Content(
            view: 'emails.welcome',
            with: [
                'body' => "
                    <h2><strong>{$heading}</strong></h2>
                    <p>Hola <strong>{$customerName}</strong>,</p>
                    <p>{$paymentMessage}</p>
                    <hr style=\"border:0;border-top:1px solid #e2e8f0;margin:20px 0;\">
                    <p>
                        <strong>Orden:</strong> {$orderNumber}<br>
                        <strong>Estado del pedido:</strong> Cancelado<br>
                        <strong>Estado del pago:</strong> {$paymentStatus}<br>
                        {$refundMessage}
                    </p>
                ",
                'buttonData' => [
                    'url' => $customerUrl,
                    'text' => 'Ver estado de mi pedido',
                ],
                'footer' => $footer,
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
