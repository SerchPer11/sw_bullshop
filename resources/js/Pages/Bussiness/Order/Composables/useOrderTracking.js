import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

function unwrapResource(resource) {
    return resource?.data ?? resource ?? null;
}

function resourceCollection(resource) {
    const value = unwrapResource(resource);

    return Array.isArray(value) ? value : [];
}

export function useOrderTracking(props) {
    const form = useForm({
        order_number: '',
        email: '',
    });

    const selectedOrderId = ref(null);
    const ordersList = computed(() => resourceCollection(props.orders));
    const guestOrder = computed(() => unwrapResource(props.order));
    const visibleOrders = computed(() => guestOrder?.value ? [guestOrder?.value] : ordersList.value);

    function submit() {
        form.post(route('order.search'));
    }

    function toggleOrder(orderId) {
        selectedOrderId.value = selectedOrderId.value === orderId ? null : orderId;
    }

    function orderItems(order) {
        return resourceCollection(order?.items);
    }

    function product(item) {
        return unwrapResource(item?.products);
    }

    function packageData(item) {
        return unwrapResource(item?.packages);
    }

    function packageProducts(item) {
        return resourceCollection(packageData(item)?.products);
    }

    function productVariants(item) {
        return resourceCollection(product(item)?.variants);
    }

    function itemName(item) {
        return product(item)?.name ?? packageData(item)?.name ?? 'Artículo no disponible';
    }

    function itemType(item) {
        return product(item) ? 'Producto' : packageData(item) ? 'Paquete' : 'Artículo';
    }

    function itemImage(item) {
        return resourceCollection(product(item)?.photos)[0]?.url
            ?? resourceCollection(packageData(item)?.photos)[0]?.url
            ?? '/placeholder.png';
    }

    function selectedVariant(item, productId = null) {
        const selections = item?.selected_package_variants ?? {};
        const variantId = productId === null
            ? item?.attributes_selected?.variant_id
            : selections[productId] ?? selections[String(productId)];
        const variants = productId === null
            ? productVariants(item)
            : packageProducts(item)
                .find(({ id }) => String(id) === String(productId))
                ?.variants;

        return resourceCollection(variants).find(({ id }) => String(id) === String(variantId));
    }

    function formatVariantAttributes(attributes) {
        if (typeof attributes === 'string') {
            try {
                attributes = JSON.parse(attributes);
            } catch {
                return [];
            }
        }

        if (!attributes || typeof attributes !== 'object') {
            return [];
        }

        return Object.entries(attributes).map(([key, value]) => {
            const label = key.replaceAll('_', ' ').replace(/\b\w/g, (letter) => letter.toUpperCase());
            const formattedValue = Array.isArray(value) ? value.join(', ') : String(value);

            return `${label}: ${formattedValue}`;
        });
    }

    function selectedVariantDetails(item, productId = null) {
        const variant = selectedVariant(item, productId);
        const fallbackName = productId === null
            ? item?.attributes_selected?.variant_name
            : null;
        const attributes = formatVariantAttributes(variant?.attributes);

        return {
            name: variant?.name ?? fallbackName ?? 'Variante no disponible',
            attributes,
        };
    }

    function paymentLabel(paymentMethod) {
        if (paymentMethod === 2) return 'Tarjeta';
        if (paymentMethod === 3) return 'POS Pop-Up';
        return 'Efectivo en evento';
    }

    function statusLabel(status) {
        return {
            pending: 'Pendiente',
            preparing: 'En preparación',
            reserved: 'Apartado',
            shipped: 'En preparación',
            completed: 'Completado',
            delivered: 'Entregado (POS)',
            cancelled: 'Cancelado',
        }[status] ?? status;
    }

    function paymentStatusLabel(status) {
        return {
            pending: 'Pendiente',
            paid: 'Pagado',
            refunded: 'Reembolsado',
            cancelled: 'Cancelado',
        }[status] ?? status;
    }

    function statusVariant(status) {
        return {
            pending: 'neo-dark',
            preparing: 'neo-neon',
            cancelled: 'neo-pink',
            delivered: 'neo-blue',
        }[status] ?? 'default';
    }

    function totalAmountColor(status) {
        return {
            pending: 'text-gray-300',
            preparing: 'text-bull-neon',
            cancelled: 'text-bull-pink',
            delivered: 'text-bull-blue',
        }[status] ?? 'text-bull-blue';
    }

    return {
        form,
        selectedOrderId,
        visibleOrders,
        guestOrder,
        submit,
        toggleOrder,
        orderItems,
        packageProducts,
        productVariants,
        itemName,
        itemType,
        itemImage,
        selectedVariantDetails,
        paymentLabel,
        statusLabel,
        paymentStatusLabel,
        statusVariant,
        totalAmountColor,
    };
}
