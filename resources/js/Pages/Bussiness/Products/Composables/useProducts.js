
import { ref, computed } from 'vue';
import { useCartStore } from '@/./Stores/useCartStore';
import { error422, messageSuccess } from '@/lib/alerts';

export const useProducts = (props = {}) => {
    const product = props?.productData?.data;
    const cartStore = useCartStore();
    const isDisabled = ref(false);
    const productCount = ref(1);

    const selectedVariant = ref(null);

    const selectVariant = (variantId) => {
        selectedVariant.value = variantId;
    };

    const isReadyToBuy = computed(() => {
        return Boolean(product) && selectedVariant.value !== null;
    });

    const incrementProductCount = () => {
        if (product?.limit_per_user && productCount.value >= product.limit_per_user) {
            return true;
        }
        productCount.value++;
    }

    const decrementProductCount = () => {
        if (productCount.value > 1) {
            productCount.value--;
        }
    }

    const handleAddToCart = () => {
        if (product?.limit_per_user && cartStore.items.some(item => item.backend_payload?.id === product.id)) {
            const existingItem = cartStore.items.find(item => item.backend_payload?.id === product.id);
            if (existingItem && existingItem.quantity >= product.limit_per_user) {
                error422("Límite alcanzado", `Solo puedes comprar ${product.limit_per_user} unidad(es) de este producto.`);
                isDisabled.value = true;
                return;
            }
        }
        try {
            const variant = product.variants.find(v => v.id === selectedVariant.value);
            cartStore.addVariant(product, variant, productCount.value);
            productCount.value = 1;
            selectedVariant.value = null;
            messageSuccess("Producto agregado al carrito", "El producto ha sido agregado a tu carrito exitosamente.");
        } catch (error) {
            error422("Error al agregar al carrito", "Ocurrió un error al intentar agregar el producto al carrito. Por favor, inténtalo de nuevo.");
        }
    };

    return {
        selectVariant,
        isReadyToBuy,
        handleAddToCart,
        selectedVariant,
        product,
        isDisabled,
        productCount,
        incrementProductCount,
        decrementProductCount
    };
};