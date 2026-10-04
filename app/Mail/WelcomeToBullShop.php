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
        return new Content(
            markdown: 'emails.welcome',
            with: [
                'body' => '<h2><strong>¡Llegaste, y ' . $this->bullName . ' también!</strong></h2> 
                <p>Bienvenido <strong>' . $fullName . '</strong>.<br>Soy Magno Chabelo, el fundador de cuatro patas detrás de BullShop.
                    Aquí sabemos la verdad: un bulldog no es una raza, es el verdadero jefe de la casa. Y queremos conocer al tuyo.</p>
                <p>Tu primer regalo ya está listo. Descarga tu e-card, ponle la foto de tu gordo y súbela a Stories. Etiquétame <a href="https://www.instagram.com/magnochabelo" target="_blank">@MagnoChabelo</a> para ir ubicando al rey de tu casa</p>',
                'buttonData' => [
                    'url' => config('app.url').'/ecard/descargar',
                    'text' => '¡Quiero mi e-card!',
                ],
                'footer' => "<p>Prepárate. Esto apenas empieza. <br>
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
