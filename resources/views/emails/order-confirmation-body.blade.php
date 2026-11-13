@php
    $customerTimezone = $order->customer_timezone ?: config('app.timezone');
    $orderDate = $order->created_at?->copy()->timezone($customerTimezone)->locale('es')->isoFormat('D [de] MMM [del] YYYY HH:mm');
@endphp

<h2><strong>¡Gracias por tu compra, {{ $order->customer_name }}!</strong></h2>
<p>Recibimos tu pedido y lo tenemos registrado para el evento.</p>

<hr style="border:0;border-top:1px solid #e2e8f0;margin:20px 0;">
<p>
    <strong>Orden:</strong> {{ $order->order_number }}<br>
    <strong>Fecha:</strong> {{ $orderDate }}<br>
    <strong>Método de pago:</strong> {{ $order->payment_method === 1 ? 'Efectivo en evento' : 'Tarjeta (Stripe)' }}<br>
    <strong>Estado del pago:</strong> {{ $order->payment_status === 'paid' ? 'Pagado' : 'Pendiente' }}<br>
    <strong>Artículos:</strong> {{ $order->items->sum('quantity') }} unidad(es)
</p>

<hr style="border:0;border-top:1px solid #e2e8f0;margin:20px 0;">
<h3>Resumen de Compra</h3>

<ul style="padding-left:18px;margin:12px 0 18px;color:#1e293b;">
    @foreach ($order->items as $item)
        @php
            $attrs = is_string($item->attributes_selected)
                ? json_decode($item->attributes_selected, true)
                : ($item->attributes_selected ?? []);

            while (is_string($attrs)) {
                $decoded = json_decode($attrs, true);
                if (json_last_error() !== JSON_ERROR_NONE || $decoded === $attrs) {
                    $attrs = [];
                    break;
                }
                $attrs = $decoded;
            }

            $lineSubtotal = number_format((float) $item->unit_price * (int) $item->quantity, 2);
        @endphp

        @if ($item->package_id)
            @php
                $packageName = $item->package?->name ?? 'Paquete BullShop';
                $selections = $attrs['selections'] ?? [];
                $packageSelectionsText = [];

                if ($item->package && $item->package->relationLoaded('products')) {
                    foreach ($item->package->products as $pkgProduct) {
                        $selectedVariantId = $selections[$pkgProduct->id] ?? $selections[(string) $pkgProduct->id] ?? null;
                        $matchedVariant = $pkgProduct->variants?->firstWhere('id', $selectedVariantId);
                        $variantLabel = $matchedVariant?->name ?? 'Estándar';
                        $packageSelectionsText[] = "{$pkgProduct->name}: {$variantLabel}";
                    }
                }
            @endphp
            <li style="margin-bottom:8px;">
                <strong>{{ $item->quantity }}x</strong> Paquete: <strong>{{ $packageName }}</strong> — <strong>${{ $lineSubtotal }} MXN</strong>
                @if (!empty($packageSelectionsText))
                    <div style="font-size:13px;color:#64748b;margin-top:2px;">
                        Incluye: {{ implode(' · ', $packageSelectionsText) }}
                    </div>
                @endif
            </li>
        @else
            @php
                $productName = $item->product?->name ?? 'Producto BullShop';
                $variantId = $attrs['variant_id'] ?? null;
                $matchedVariant = $item->product?->variants?->firstWhere('id', $variantId);
                $variantName = $attrs['variant_name'] ?? $matchedVariant?->name ?? '';
            @endphp
            <li style="margin-bottom:8px;">
                <strong>{{ $item->quantity }}x</strong> {{ $productName }}@if($variantName) ({{ $variantName }})@endif — <strong>${{ $lineSubtotal }} MXN</strong>
            </li>
        @endif
    @endforeach
</ul>

<p style="margin-top:12px;">
    <strong>Subtotal:</strong> ${{ number_format((float) $order->subtotal, 2) }} MXN<br>
    @if ((float) $order->discount_amount > 0)
        <strong>Descuento:</strong> -${{ number_format((float) $order->discount_amount, 2) }} MXN<br>
    @endif
    @if ((float) $order->shipping_cost > 0)
        <strong>Envío:</strong> ${{ number_format((float) $order->shipping_cost, 2) }} MXN<br>
    @endif
    <strong>Total:</strong> ${{ number_format((float) $order->total_amount, 2) }} MXN
</p>

<hr style="border:0;border-top:1px solid #e2e8f0;margin:20px 0;">
<h3>QR para el personal BullShop</h3>
<p>Presenta este código al recoger tu pedido. El personal lo utilizará para localizar y entregar tu orden.</p>
