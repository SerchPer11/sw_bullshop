
import { ref, computed } from 'vue';
import { useCartStore } from '@/./Stores/useCartStore';
import { error422, messageSuccess } from '@/lib/alerts';


export const usePackages = (props = {}) => {
    const pack = props?.packageData?.data;
    const cartStore = useCartStore();
    const isDisabled = ref(false);
    const selections = ref({});
    

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
            cartStore.addBundle(pack, selections.value);
            selections.value = {};
            messageSuccess("Paquete agregado al carrito", "El paquete ha sido agregado a tu carrito exitosamente.");
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
        isDisabled
    };
}