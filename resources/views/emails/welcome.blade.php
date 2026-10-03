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
        
        table {
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            border: none !important;
        }
        
        h1, h2, h3 { text-transform: uppercase !important; font-weight: 900 !important; color: #054C68 !important; }
        
        /* EL HACK ANTI-GMAIL (Ahora con base blanca) */
        .bg-base {
            background: #ffffff linear-gradient(#ffffff, #ffffff) !important;
        }
        .force-blue {
            background: #054C68 linear-gradient(#054C68, #054C68) !important;
        }
        .force-white {
            background: #ffffff linear-gradient(#ffffff, #ffffff) !important;
        }

        .button-primary {
            background: #054C68 linear-gradient(#054C68, #054C68) !important;
            border: 2px solid #FDE7DD !important;
            color: #ffffff !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            box-shadow: 4px 4px 0px 0px #002731 !important;
            border-radius: 0 !important;
            display: inline-block !important;
            padding: 10px 20px !important;
            text-decoration: none !important;
            transition: all 0.2s ease-in-out !important;
        }

        /* Modo oscuro para clientes que sí respetan el código (Apple Mail) */
        @media (prefers-color-scheme: dark) {
            .bg-base { background: #021a24 linear-gradient(#021a24, #021a24) !important; }
            .force-blue { background: #D4E818 linear-gradient(#D4E818, #D4E818) !important; }
            .force-white { background: #054C68 linear-gradient(#054C68, #054C68) !important; border-color: #D4E818 !important; }
            h1, h2, h3, p, span, td, .footer p, .header a { color: #ffffff !important; }
        }
    </style>
</head>
<body class="bg-base" style="box-sizing: border-box; font-family: 'Arial Black', Impact, -apple-system, BlinkMacSystemFont, sans-serif; position: relative; -webkit-text-size-adjust: none; color: #054C68; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">

    <table class="wrapper bg-base" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; margin: 0; padding: 0; width: 100%; border: none;">
        <tr>
            <td align="center" style="box-sizing: border-box; border: none;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; margin: 0; padding: 0; width: 100%; border: none;">
                    
                    <tr>
                        <td class="header bg-base" style="box-sizing: border-box; padding: 40px 0 20px; text-align: center; border: none;">
                            <a href="{{ config('app.url') }}" style="box-sizing: border-box; display: inline-block; font-weight: 900; color: #054C68; font-size: 24px; text-decoration: none;">
                                <img src="{{ asset('Images/Logo/logo_pink.png') }}" class="logo" alt="BullShop" style="width: 220px; max-width: 100%; height: auto;"/>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td class="body bg-base" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; margin: 0; padding: 0; width: 100%; border: none !important;">
                            
                            <table class="inner-body force-blue" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; width: 570px; border: none;">
                                <tr>
                                    <td style="padding-bottom: 8px; padding-right: 8px; border: none;">
                                        
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border: none;">
                                            <tr>
                                                <td class="content-cell-inner force-white" style="box-sizing: border-box; font-weight: normal; font-family: -apple-system, BlinkMacSystemFont, Arial, sans-serif; max-width: 100vw; padding: 40px; color: #054C68; border: 4px solid #054C68;">
                                                    
                                                    {!! $body !!}
                                                    
                                                    @if (isset($buttonData))
                                                        <x-mail::button :url="$buttonData['url']" class="button-primary">
                                                            {{ $buttonData['text'] }}
                                                        </x-mail::button>
                                                    @endif

                                                    @if(isset($footer))
                                                    <div style="margin-top: 32px; padding-top: 24px; border-top: 4px solid #054C68;">
                                                        {!! $footer !!}
                                                    </div>
                                                    @endif

                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td class="bg-base" style="box-sizing: border-box; border: none;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; border: none;">
                                <tr>
                                    <td class="content-cell" align="center" style="box-sizing: border-box; max-width: 100vw; padding: 32px; border: none;">
                                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, Arial, sans-serif; font-weight: 900; line-height: 1.5em; margin-top: 0; color: #054C68; font-size: 13px; text-transform: uppercase; text-align: center; letter-spacing: 1px;">
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