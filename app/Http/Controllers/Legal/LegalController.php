<?php

namespace App\Http\Controllers\Legal;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class LegalController extends Controller
{
    protected string $source;

    public function __construct()
    {
        $this->source = 'Legal/';
    }

    public function privacy(): Response
    {
        return Inertia::render("{$this->source}Document", [
            'title' => 'Aviso de Privacidad',
            'badge' => 'Privacidad y Datos',
            'updatedAt' => 'Noviembre 2026',
            'currentRoute' => 'legal.privacy',
            // AQUÍ PUEDES EDITAR EL TEXTO O HTML DE TU AVISO DE PRIVACIDAD
            'content' => <<<'HTML'
                <h2>1. Identidad y Domicilio del Responsable</h2>
                <p><strong>BullShop</strong> (en adelante, "BullShop"), con domicilio de operaciones en Ciudad de México, México, y correo electrónico de contacto <a href="mailto:bullshop.mex@gmail.com">bullshop.mex@gmail.com</a>, es el responsable del uso y protección de sus datos personales, en cumplimiento con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP).</p>

                <h2>2. Datos Personales que Recabamos</h2>
                <p>Para llevar a cabo las finalidades descritas en el presente aviso de privacidad, utilizaremos los siguientes datos personales:</p>
                <ul>
                    <li><strong>Datos de identificación y contacto:</strong> Nombre completo, dirección de correo electrónico, número telefónico y domicilio de entrega.</li>
                    <li><strong>Datos de transacción y pago:</strong> Información relacionada con sus pedidos. Los datos financieros (tarjetas de crédito o débito) son procesados directamente de forma encriptada a través de nuestra pasarela de pagos certificada (<strong>Stripe</strong>), por lo que BullShop no almacena números completos de tarjetas ni códigos de seguridad (CVV).</li>
                </ul>

                <h2>3. Finalidades del Tratamiento</h2>
                <p>Los datos personales que recabamos de usted los utilizaremos para las siguientes finalidades primarias y necesarias para el servicio que solicita:</p>
                <ul>
                    <li>Procesar, confirmar, enviar y entregar sus compras o apartados de productos y paquetes.</li>
                    <li>Procesar pagos seguros mediante nuestra pasarela de pago (Stripe).</li>
                    <li>Brindar seguimiento a pedidos, cambios de talla, garantías o devoluciones.</li>
                    <li>Atender dudas, comentarios o solicitudes de soporte a través de nuestros canales oficiales.</li>
                </ul>
                <p>De manera adicional, utilizaremos su información para finalidades secundarias como el envío de lanzamientos exclusivos (Drops), promociones y noticias del club BullShop. Usted puede cancelar la suscripción en cualquier momento.</p>

                <h2>4. Transferencia de Datos Personales</h2>
                <p>Sus datos personales únicamente son compartidos con terceros estrictamente necesarios para la prestación del servicio contratado:</p>
                <ul>
                    <li><strong>Pasarela de pagos (Stripe, Inc.):</strong> Para el procesamiento seguro de transacciones electrónicas y prevención de fraude.</li>
                    <li><strong>Empresas de mensajería y logística:</strong> Para hacer llegar sus pedidos al domicilio indicado.</li>
                </ul>

                <h2>5. Derechos ARCO</h2>
                <p>Usted tiene derecho a conocer qué datos personales tenemos de usted, para qué los utilizamos y las condiciones del uso que les damos (Acceso). Asimismo, es su derecho solicitar la corrección de su información personal (Rectificación); que la eliminemos de nuestros registros (Cancelación); así como oponerse al uso de sus datos personales para fines específicos (Oposición).</p>
                <p>Para ejercer cualquiera de los derechos ARCO, envíe una solicitud al correo electrónico <a href="mailto:bullshop.mex@gmail.com">bullshop.mex@gmail.com</a> indicando su nombre completo, número de pedido (en caso de aplicar) y la descripción clara de los datos sobre los que busca ejercer sus derechos.</p>

                <h2>6. Uso de Cookies</h2>
                <p>Nuestro sitio web utiliza cookies técnicas y de sesión esenciales para mantener los productos en su bolsa de compras, autenticar su cuenta y garantizar el correcto funcionamiento del proceso de pago.</p>
            HTML,
        ]);
    }

    public function terms(): Response
    {
        return Inertia::render("{$this->source}Document", [
            'title' => 'Términos y Condiciones',
            'badge' => 'Condiciones de Uso',
            'updatedAt' => 'Noviembre 2026',
            'currentRoute' => 'legal.terms',
            // AQUÍ PUEDES EDITAR EL TEXTO O HTML DE TUS TÉRMINOS Y CONDICIONES
            'content' => <<<'HTML'
                <h2>1. Aceptación de los Términos</h2>
                <p>Bienvenido a <strong>BullShop</strong>. Al acceder, navegar y realizar una compra en nuestro sitio web, usted acepta estar sujeto a los presentes Términos y Condiciones. Si no está de acuerdo con alguna parte de estos términos, le solicitamos no utilizar nuestros servicios.</p>

                <h2>2. Productos y Disponibilidad (Drops)</h2>
                <p>BullShop comercializa prendas de streetwear y equipamiento ergonómico diseñado para bulldogs y sus dueños en ediciones limitadas ("Drops") y paquetes especiales.</p>
                <ul>
                    <li>Todas las órdenes están sujetas a disponibilidad de inventario en tiempo real.</li>
                    <li>Nos esforzamos por mostrar los colores y texturas de nuestros productos con la mayor precisión posible; sin embargo, pueden existir ligeras variaciones dependiendo del monitor o pantalla de su dispositivo.</li>
                    <li>Es responsabilidad del cliente revisar la guía de tallas antes de confirmar su pedido.</li>
                </ul>

                <h2>3. Precios y Pagos con Stripe</h2>
                <ul>
                    <li>Todos los precios mostrados en el sitio están expresados en <strong>Pesos Mexicanos (MXN)</strong> e incluyen los impuestos aplicables, salvo que se indique lo contrario.</li>
                    <li>Los costos de envío se calculan y muestran de manera desglosada antes de finalizar la compra en el Checkout.</li>
                    <li>Los pagos en línea se procesan de manera segura a través de <strong>Stripe</strong>. Al proporcionar un método de pago, usted declara y garantiza estar autorizado para utilizar dicho medio de pago.</li>
                    <li>El pedido se considera confirmado únicamente cuando el pago ha sido aprobado exitosamente por el procesador de pagos o validado por nuestro staff en modalidades presenciales autorizadas.</li>
                </ul>

                <h2>4. Cupones y Promociones</h2>
                <p>Los códigos promocionales o cupones de descuento son válidos únicamente durante la vigencia indicada, no son acumulables con otras promociones ni canjeables por dinero en efectivo.</p>

                <h2>5. Propiedad Intelectual</h2>
                <p>Todo el contenido de este sitio web, incluyendo diseños textiles, patronaje ergonómico, logotipos, gráficos, fotografías, textos y software, es propiedad exclusiva de <strong>BullShop Indie Brand</strong> y está protegido por las leyes mexicanas e internacionales de propiedad industrial y derechos de autor.</p>

                <h2>6. Legislación Aplicable y Jurisdicción</h2>
                <p>Estos Términos y Condiciones se rigen e interpretan de acuerdo con las leyes vigentes en los Estados Unidos Mexicanos y la Ley Federal de Protección al Consumidor. Para cualquier controversia, las partes se someten a la jurisdicción aplicable en la Ciudad de México.</p>
            HTML,
        ]);
    }

    public function refunds(): Response
    {
        return Inertia::render("{$this->source}Document", [
            'title' => 'Política de Devoluciones y Reembolsos',
            'badge' => 'Cambios y Garantías',
            'updatedAt' => 'Noviembre 2026',
            'currentRoute' => 'legal.refunds',
            // AQUÍ PUEDES EDITAR EL TEXTO O HTML DE TU POLÍTICA DE DEVOLUCIONES
            'content' => <<<'HTML'
                <h2>1. Plazo para Cambios y Devoluciones</h2>
                <p>En <strong>BullShop</strong> queremos que tanto tú como tu bulldog estén completamente satisfechos con su equipamiento. Cuentas con un plazo de <strong>15 días naturales</strong> a partir de la fecha en que recibiste tu pedido o lo recogiste en evento para solicitar un cambio de talla, cambio por defecto de fabricación o devolución.</p>

                <h2>2. Condiciones de los Productos (Higiene y Uso)</h2>
                <p>Por razones de higiene y salud animal, para que un producto sea elegible para cambio o devolución debe cumplir estrictamente con los siguientes requisitos:</p>
                <ul>
                    <li>Estar completamente nuevo, sin indicios de uso en paseo, sin olores, sin manchas y <strong>libre de pelo de mascota</strong>.</li>
                    <li>No haber sido lavado ni modificado.</li>
                    <li>Conservar su empaque original, etiquetas colgantes y accesorios completos (en caso de paquetes).</li>
                </ul>
                <p><em>Nota:</em> Te recomendamos probar la prenda a tu bulldog en un espacio interior limpio y durante un par de minutos únicamente para verificar el ajuste.</p>

                <h2>3. Defectos de Fabricación</h2>
                <p>Si tu pieza presenta algún defecto de costura, herraje o fabricación al momento de recibirla, BullShop cubrirá el 100% de los gastos de envío para reponer la pieza por una nueva o, en caso de agotarse el stock del Drop, realizar el reembolso íntegro de tu compra.</p>

                <h2>4. Proceso para Solicitar un Cambio o Reembolso</h2>
                <ol>
                    <li>Envía un correo a <a href="mailto:bullshop.mex@gmail.com">bullshop.mex@gmail.com</a> con el asunto <strong>"Devolución / Cambio - Pedido #[Número de Pedido]"</strong>.</li>
                    <li>Adjunta fotografías claras del estado actual del producto y sus etiquetas.</li>
                    <li>Nuestro equipo revisará tu solicitud en un plazo máximo de 24 a 48 horas hábiles y te enviará las instrucciones de envío o entrega.</li>
                </ol>

                <h2>5. Procesamiento de Reembolsos (Stripe)</h2>
                <p>Una vez recibido e inspeccionado el producto devuelto en nuestro taller, te notificaremos por correo electrónico la aprobación de tu reembolso:</p>
                <ul>
                    <li>El reembolso se aplicará automáticamente al <strong>mismo método de pago original</strong> utilizado en la compra a través de <strong>Stripe</strong>.</li>
                    <li>Dependiendo de tu institución bancaria, el saldo a favor puede tardar entre <strong>5 y 10 días hábiles</strong> en verse reflejado en tu estado de cuenta.</li>
                    <li>En cambios de talla donde no exista defecto de fábrica, el costo de las guías de envío corre por cuenta del comprador.</li>
                </ul>
            HTML,
        ]);
    }

    public function shipping(): Response
    {
        return Inertia::render("{$this->source}Document", [
            'title' => 'Política de Envíos y Entregas',
            'badge' => 'Logística y Entregas',
            'updatedAt' => 'Noviembre 2026',
            'currentRoute' => 'legal.shipping',
            // AQUÍ PUEDES EDITAR EL TEXTO O HTML DE TU POLÍTICA DE ENVÍOS
            'content' => <<<'HTML'
                <h2>1. Cobertura de Envíos</h2>
                <p>Realizamos envíos a toda la República Mexicana desde nuestro centro de operaciones en la Ciudad de México, además de contar con modalidad de recolección presencial (Pick-Up) en eventos oficiales y Pop-Ups anunciados en nuestra tienda.</p>

                <h2>2. Tiempos de Procesamiento</h2>
                <ul>
                    <li>Todos los pedidos pagados y confirmados se procesan y empacan en un plazo de <strong>1 a 3 días hábiles</strong>.</li>
                    <li>Los pedidos realizados en fines de semana o días festivos oficiales en México se comienzan a procesar al siguiente día hábil.</li>
                </ul>

                <h2>3. Tiempos Estimados y Costos de Entrega</h2>
                <ul>
                    <li><strong>Envío Estándar Nacional:</strong> Tiempo estimado de entrega de 3 a 6 días hábiles una vez despachado el paquete.</li>
                    <li><strong>Entregas en Eventos / Pop-Up CDMX:</strong> Sin costo de envío. Podrás recoger tu pedido presentando tu código QR o número de orden e identificación oficial en la fecha y sede del evento.</li>
                </ul>
                <p>El costo exacto del envío se calcula automáticamente en la pantalla de Checkout antes de realizar tu pago.</p>

                <h2>4. Rastreo de Pedidos</h2>
                <p>En cuanto tu compra sea confirmada recibirás un correo electrónico con los detalles de tu orden. Además, puedes consultar el estatus en tiempo real desde la sección <strong>"Rastrear pedido"</strong> en nuestra plataforma utilizando tu número de orden y correo electrónico.</p>

                <h2>5. Paquetes Extraviados o Domicilios Incorrectos</h2>
                <p>Es responsabilidad del comprador verificar que los datos de dirección, código postal y referencias sean correctos al momento de finalizar la compra. Si detectas un error en tu dirección de entrega, contáctanos de inmediato a <a href="mailto:bullshop.mex@gmail.com">bullshop.mex@gmail.com</a> antes de que tu pedido sea despachado.</p>
            HTML,
        ]);
    }
}
