
import { ref, computed } from 'vue';
import { useCartStore } from '@/./Stores/useCartStore';
import { error422 } from '@/lib/alerts';


export const usePackages = (props = {}) => {
    const pack = props?.packageData?.data;
    const cartStore = useCartStore();
    const isDisabled = ref(false);
    const selections = ref({});
    const showCartModal = ref(false);
    const addedItem = ref(null);
    

    const selectVariant = (productId, variantId) => {
        selections.value[productId] = variantId;
    };

    const isReadyToBuy = computed(() => {
        if (!pack?.products || pack?.products.length === 0) return false;

        return Object.keys(selections.value).length === pack?.products.length;
    });

    const handleAddToCart = () => {
        if (pack?.limit_per_user && cartStore.items.some(item => item.backend_payload?.id === pack.id)) {
            const existingItem = cartStore.items.find(item => item.backend_payload?.id === pack.id);
            if (existingItem && existingItem.quantity >= pack.limit_per_user) {
                error422("Límite alcanzado", `Solo puedes comprar ${pack.limit_per_user} unidad(es) de este paquete.`);
                isDisabled.value = true;
                return;
            }
        }
        try {
            const selectedLabels = (pack?.products || [])
                .map((product) => {
                    const variantId = selections.value[product.id];
                    const variant = product.variants?.find((v) => v.id === variantId);
                    return variant ? `${product.name}: ${variant.name}` : null;
                })
                .filter(Boolean)
                .join(' | ');

            const unitPrice = Number(pack?.display_price ?? 0);

            cartStore.addBundle(pack, selections.value);

            addedItem.value = {
                name: pack.name,
                variantName: selectedLabels || 'Kit Personalizado',
                quantity: 1,
                unitPrice,
                subtotal: unitPrice,
                image: pack.photos?.[0]?.url || '/placeholder.png',
            };

            showCartModal.value = true;
            selections.value = {};
        } catch (error) {
            error422("Error al agregar al carrito", "Ocurrió un error al intentar agregar el paquete al carrito. Por favor, inténtalo de nuevo.");
        }

    };

    return {
        selectVariant,
        isReadyToBuy,
        handleAddToCart,
        selections,
        pack,
        isDisabled,
        showCartModal,
        addedItem,
    };
}