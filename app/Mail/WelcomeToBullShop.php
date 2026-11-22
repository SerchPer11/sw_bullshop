<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Bussiness\Lead;
use Illuminate\Mail\Mailables\Address;
use App\Models\Bussiness\Coupon;

class WelcomeToBullShop extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Lead $lead;
    public ?string $bullName;

    /**
     * Create a new message instance.
     */
    public function __construct(Lead $lead, ?string $bullName = null)
    {
        $this->lead = $lead;
        $this->bullName = $bullName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {


        return new Envelope(
            replyTo: [
                new Address('bullshop.mex@gmail.com', 'Soporte BullShop'),
            ],
            subject: 'Ya estás adentro. Magno te saluda.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $name = explode(' ', $this->lead->name ?? '');
        $lastName = explode(' ', $this->lead->last_name ?? '');
        $fullName = $name[0] . ' ' . $lastName[0];
        $coupon = 'BSCLUB05'; // Default coupon code
        return new Content(
            markdown: 'emails.welcome',
            with: [
                'body' => '<h2><strong>¡Llegaste, y ' . $this->bullName . ' también!</strong></h2> 
                <p>Bienvenido <strong>' . $fullName . '</strong>.<br>Soy Magno Chabelo, el fundador de cuatro patas detrás de BullShop.
                    Aquí sabemos la verdad: un bulldog no es una raza, es el verdadero jefe de la casa. Y queremos conocer al tuyo.</p>
                <p>Tu primer regalo ya está listo. Usa este cupón para obtener un 5% de descuento en tu primera compra.</p>
                <p style="text-align: center;">
        <strong style="background-color: #ebf8ff; color: #2b6cb0; padding: 6px 14px; border-radius: 6px; display: inline-block;">' . $coupon . '</strong>
      </p>',
                'footer' => "<p>Esto apenas empieza. <br>
                                Magno Chabelo & El equipo BullShop</p>",
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
