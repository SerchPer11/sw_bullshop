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

class POSReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address('bullshop.mex@gmail.com', 'Soporte BullShop'),
            ],
            subject: "¡Gracias por tu compra en el Pop-Up de BullShop! ({$this->order->order_number})",
        );
    }

    public function content(): Content
    {
        $this->order->loadMissing(['items.product', 'items.package']);

        $customerUrl = URL::temporarySignedRoute(
            'order.customer.show',
            now()->addDays(30),
            ['orderNumber' => $this->order->order_number],
        );

        $orderNumber = e($this->order->order_number);
        $totalAmount = number_format((float) $this->order->total_amount, 2);

        $itemsHtml = '';
        foreach ($this->order->items as $item) {
            $attrs = is_string($item->attributes_selected)
                ? json_decode($item->attributes_selected, true)
                : $item->attributes_selected;

            $lineTotal = number_format((float) $item->unit_price * (int) $item->quantity, 2);

            if ($item->package_id) {
                $packageName = e($item->package?->name ?? 'Paquete BullShop');
                $label = "Paquete: {$packageName}";
            } else {
                $productName = e($item->product?->name ?? 'Producto BullShop');
                $variantName = e($attrs['variant_name'] ?? '');
                $label = $variantName ? "{$productName} ({$variantName})" : $productName;
            }

            $itemsHtml .= "<li><strong>{$item->quantity}x</strong> {$label} — \${$lineTotal} MXN</li>";
        }

        return new Content(
            view: 'emails.welcome',
            with: [
                'body' => "
                    <h2><strong>¡Gracias por tu compra en el Pop-Up de BullShop!</strong></h2>
                    <p>Tu recibo por la cantidad de <strong>\${$totalAmount} MXN</strong> está confirmado. Fue increíble conocer a tu bulldog en persona. Mantente atento a este correo, por aquí te avisaremos en exclusiva sobre nuestros próximos drops online y mercancía de edición limitada.</p>
                    <hr style=\"border:0;border-top:1px solid #e2e8f0;margin:20px 0;\">
                    <p>
                        <strong>Orden:</strong> {$orderNumber}<br>
                        <strong>Modalidad:</strong> Venta Pop-Up (POS)<br>
                        <strong>Estado:</strong> Pagado y Entregado
                    </p>
                    <ul style=\"padding-left:20px;margin:12px 0;\">
                        {$itemsHtml}
                    </ul>
                    <p><strong>Total pagado:</strong> \${$totalAmount} MXN</p>
                ",
                'buttonData' => [
                    'url' => $customerUrl,
                    'text' => 'Ver mi recibo digital',
                ],
                'footer' => '<p>Etiquétanos en Instagram <a href="https://www.instagram.com/magnochabelo" target="_blank">@MagnoChabelo</a> estrenando tu nuevo gear.</p><p>Magno Chabelo &amp; El equipo BullShop</p>',
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
