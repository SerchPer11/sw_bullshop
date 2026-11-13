<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body { width: 100% !important; }
            .footer { width: 100% !important; }
        }
        table { border-collapse: collapse !important; border-spacing: 0 !important; border: none !important; }
        
        /* Tipografía limpia. Sin colores forzados para que el cliente decida. */
        h1, h2, h3 { text-transform: uppercase !important; font-weight: 900 !important; }
        p, span, td, li { font-size: 16px !important; }
        
        /* Botón simple que sobrevive a cualquier modo oscuro */
        .button-primary {
            display: inline-block !important;
            padding: 12px 25px !important;
            background-color: #222222 !important; /* Negro/Gris muy oscuro */
            color: #ffffff !important;
            text-decoration: none !important;
            text-transform: uppercase !important;
            font-weight: bold !important;
            border-radius: 4px !important;
            border: 1px solid #111111 !important;
        }

        .qr-table {
            border-collapse: collapse !important;
            margin: 0 auto;
            background: #ffffff;
        }

        .qr-table td {
            width: 6px !important;
            height: 6px !important;
            padding: 0 !important;
            line-height: 0 !important;
            font-size: 0 !important;
        }
    </style>
</head>
<body style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; line-height: 1.6; margin: 0; padding: 0; width: 100% !important; background-color: #f8fafc; color: #333333;">

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0; padding: 0; width: 100%; background-color: #f8fafc;">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0; padding: 0; width: 100%;">
                    
                    <!-- HEADER -->
                    <tr>
                        <td class="header" style="padding: 40px 0 20px; text-align: center;">
                            <a href="{{ config('app.url') }}" style="display: inline-block; text-decoration: none;">
                                <!-- CONSEJO ZEN: Usa un logo en PNG con un contorno/stroke blanco de 2px. Así se verá perfecto en blanco o en negro. -->
                                <img src="{{ asset('Images/Logo/logo_pink.png') }}" class="logo" alt="BullShop" style="width: 200px; max-width: 100%; height: auto;"/>
                            </a>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0; width: 100%;">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; width: 570px; background-color: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td class="content-cell-inner" style="padding: 40px;">

                                        @if (isset($image))
                                            <img src="{{ $image }}" alt="Image" style="width: 100%; height: auto;"/>
                                        @endif

                                        {!! $body !!}

                                        @if (isset($qrHtml))
                                            <div style="margin-top: 28px; padding-top: 24px; overflow:auto; text-align:center;">
                                                {!! $qrHtml !!}
                                            </div>
                                        @elseif (isset($qr))
                                            <div style="margin-top: 24px; text-align: center;">
                                                <img src="{{ $message->embedData($qr, 'bullshop-order-qr.png', 'image/png') }}" alt="{{ $qrAlt ?? 'QR Code' }}" style="width: 220px; max-width: 100%; height: auto;"/>
                                            </div>
                                        @elseif (isset($qrContentId))
                                            <div style="margin-top: 24px; text-align: center;">
                                                <img src="cid:{{ $qrContentId }}" alt="{{ $qrAlt ?? 'QR Code' }}" style="width: 220px; max-width: 100%; height: auto;"/>
                                            </div>
                                        @endif

                                        @if (isset($buttonData))
                                            <div style="border-top: 1px solid #e2e8f0; margin-top: 28px; padding-top: 24px;">
                                            <div style="text-align: center; margin-top: 35px; margin-bottom: 20px;">
                                                <a href="{{ $buttonData['url'] }}" class="button-primary">
                                                    {{ $buttonData['text'] }}
                                                </a>
                                            </div>
                                            </div>
                                        @endif

                                        @if(isset($footer))
                                        <div style="margin-top: 40px; padding-top: 24px; border-top: 1px solid #e2e8f0; font-size: 14px; color: #718096;">
                                            {!! $footer !!}
                                        </div>
                                        @endif

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding-top: 20px;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                                <tr>
                                    <td class="content-cell" align="center" style="padding: 32px;">
                                        <p style="font-size: 12px; text-transform: uppercase; color: #a0aec0; letter-spacing: 1px; margin: 0;">
                                            © {{ date('Y') }} BULLSHOP. ACCESO RESTRINGIDO.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>