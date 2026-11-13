<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoApiTransport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        VerifyEmail::toMailUsing(function (object $notifiable, string $url): MailMessage {
            $name = e($notifiable->name ?? 'amigo');

            return (new MailMessage)
                ->subject('Confirma tu correo y entra a BullShop')
                ->markdown('emails.welcome', [
                    'body' => '<h2><strong>¡Ya casi estás dentro!</strong></h2>
                    <p>Hola <strong>'.$name.'</strong>.<br>Confirma tu correo para activar tu cuenta y comenzar tu experiencia en BullShop.</p>
                    <p>Este enlace es personal y solo puede utilizarse para verificar tu cuenta.</p>',
                    'buttonData' => [
                        'url' => $url,
                        'text' => 'Confirmar mi correo',
                    ],
                    'footer' => '<p>Nos vemos dentro.<br>
                                    Magno Chabelo & El equipo BullShop</p>',
                ]);
        });

        Mail::extend('brevo', function () {
            return new BrevoApiTransport(config('services.brevo.key'));
        });

        // Forzar HTTPS en producción para Railway
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
