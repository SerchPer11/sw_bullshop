<?php

namespace App\Mail;

use App\Models\Bussiness\Order;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address('bullshop.mex@gmail.com', 'Soporte BullShop'),
            ],
            subject: "Confirmación de tu pedido {$this->order->order_number} en BullShop",
        );
    }

    public function content(): Content
    {
        $this->order->loadMissing([
            'items.product.variants',
            'items.package.products.variants',
            'coupon',
        ]);

        $customerUrl = URL::temporarySignedRoute(
            'order.customer.show',
            now()->addDays(30),
            ['orderNumber' => $this->order->order_number],
        );

        return new Content(
            view: 'emails.welcome',
            with: [
                'body' => view('emails.order-confirmation-body', [
                    'order' => $this->order,
                ])->render(),
                'buttonData' => [
                    'url' => $customerUrl,
                    'text' => 'Consultar mi pedido',
                ],
                'qrHtml' => $this->qrHtml(),
                'qrAlt' => "Código QR de la orden {$this->order->order_number}",
                'footer' => '<p>Presenta el código QR al personal BullShop durante la entrega.</p><p>Magno Chabelo &amp; El equipo BullShop</p>',
            ],
        );
    }

    private function qrHtml(): string
    {
        $staffUrl = route('order.staff.show', ['orderNumber' => $this->order->order_number]);

        $qrCode = new QRCode(new QROptions(['addQuietzone' => true]));
        $qrCode->render($staffUrl);
        $matrix = $qrCode->getQRMatrix()->getBooleanMatrix();
        $html = '<table class="qr-table" role="presentation" cellspacing="0" cellpadding="0">';

        foreach ($matrix as $row) {
            $html .= '<tr>';

            foreach ($row as $isDark) {
                $background = $isDark ? '#054C68' : '#FFFFFF';
                $html .= "<td bgcolor=\"{$background}\"></td>";
            }

            $html .= '</tr>';
        }

        return $html.'</table>';
    }

    private function qrCodeBinary(): string
    {
        $staffUrl = route('order.staff.show', ['orderNumber' => $this->order->order_number]);

        $qrCode = (new QRCode(new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'outputBase64' => false,
            'scale' => 8,
            'bgColor' => [255, 255, 255],
            'moduleValues' => [
                QRMatrix::M_DATA_DARK => [5, 76, 104],
                QRMatrix::M_FINDER_DARK => [5, 76, 104],
                QRMatrix::M_ALIGNMENT_DARK => [5, 76, 104],
                QRMatrix::M_TIMING_DARK => [5, 76, 104],
                QRMatrix::M_FORMAT_DARK => [5, 76, 104],
                QRMatrix::M_VERSION_DARK => [5, 76, 104],
                QRMatrix::M_DARKMODULE => [5, 76, 104],
                QRMatrix::M_SEPARATOR_DARK => [5, 76, 104],
            ],
        ])))->render($staffUrl);

        $image = imagecreatefromstring($qrCode);
        $blue = imagecolorallocate($image, 5, 76, 104);

        for ($pixelY = 0; $pixelY < imagesy($image); $pixelY++) {
            for ($pixelX = 0; $pixelX < imagesx($image); $pixelX++) {
                $pixelColor = imagecolorsforindex($image, imagecolorat($image, $pixelX, $pixelY));

                if ($pixelColor['red'] + $pixelColor['green'] + $pixelColor['blue'] < 600) {
                    imagesetpixel($image, $pixelX, $pixelY, $blue);
                }
            }
        }

        ob_start();
        imagepng($image);

        return (string) ob_get_clean();
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn (): string => $this->qrCodeBinary(),
                'bullshop-order-qr.png',
            )->withMime('image/png'),
        ];
    }
}
