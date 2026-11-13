import { computed, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { error422 } from '@/lib/alerts';

export function usePOS(props) {
    const activeTab = ref('products'); // 'products' | 'packages'
    const searchQuery = ref('');
    const currentPage = ref(1);
    const itemsPerPage = 6;

    const cartItems = ref([]);
    // Guarda las selecciones temporales de variantes por paquete: { [packageId]: { [productId]: variant } }
    const bundleSelections = ref({});

    const form = useForm({
        email: '',
        customer_name: '',
        items: [],
    });

    const products = computed(() => props.productsData?.data ?? props.productsData ?? []);
    const packages = computed(() => props.packagesData?.data ?? props.packagesData ?? []);

    // Reiniciar paginación al cambiar tab o búsqueda
    watch([activeTab, searchQuery], () => {
        currentPage.value = 1;
    });

    const getVariants = (product) => product?.variants?.data ?? product?.variants ?? [];

    const getPackageProducts = (pkg) => pkg?.products?.data ?? pkg?.products ?? [];

    const getItemImage = (item) => {
        const photos = item?.photos?.data ?? item?.photos ?? [];
        return photos[0]?.url ?? '/Images/Logo/logo_pink.png';
    };

    const filteredProducts = computed(() => {
        const query = searchQuery.value.trim().toLowerCase();
        if (!query) {
            return products.value;
        }

        return products.value.filter((product) => {
            const matchesName = product.name?.toLowerCase().includes(query);
            const variants = getVariants(product);
            const matchesVariant = variants.some(
                (v) =>
                    v.name?.toLowerCase().includes(query) ||
                    v.sku?.toLowerCase().includes(query)
            );

            return matchesName || matchesVariant;
        });
    });

    const filteredPackages = computed(() => {
        const query = searchQuery.value.trim().toLowerCase();
        if (!query) {
            return packages.value;
        }

        return packages.value.filter((pkg) => {
            const matchesName = pkg.name?.toLowerCase().includes(query);
            const pkgProducts = getPackageProducts(pkg);
            const matchesInnerProduct = pkgProducts.some((p) =>
                p.name?.toLowerCase().includes(query)
            );

            return matchesName || matchesInnerProduct;
        });
    });

    // Lista activa según el Tab seleccionado + Paginación cliente (sin perder el carrito)
    const activeList = computed(() =>
        activeTab.value === 'products' ? filteredProducts.value : filteredPackages.value
    );

    const totalPages = computed(() =>
        Math.max(1, Math.ceil(activeList.value.length / itemsPerPage))
    );

    const paginatedItems = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        return activeList.value.slice(start, start + itemsPerPage);
    });

    const goToPage = (page) => {
        if (page >= 1 && page <= totalPages.value) {
            currentPage.value = page;
        }
    };

    // Calcula cuántas unidades de una variante están reservadas actualmente en el ticket
    // (sumando tanto productos individuales como paquetes que incluyan esa variante)
    const getCartQuantity = (variantId) => {
        return cartItems.value.reduce((total, item) => {
            if (item.type === 'variant' && item.variant_id === variantId) {
                return total + item.quantity;
            }
            if (item.type === 'bundle' && item.selections) {
                const usesVariant = Object.values(item.selections).includes(variantId);
                if (usesVariant) {
                    return total + item.quantity;
                }
            }
            return total;
        }, 0);
    };

    const availableStock = (variant) => {
        const baseStock = Number(variant?.stock ?? 0);
        return Math.max(0, baseStock - getCartQuantity(variant?.id));
    };

    // 1. Agregar Producto Individual (Variante)
    const addVariantToCart = (product, variant) => {
        if (availableStock(variant) <= 0) {
            return;
        }

        const uniqueKey = `variant-${variant.id}`;
        const existing = cartItems.value.find((item) => item.uniqueKey === uniqueKey);

        if (existing) {
            existing.quantity += 1;
            return;
        }

        cartItems.value.push({
            uniqueKey,
            type: 'variant',
            variant_id: variant.id,
            product_id: product.id,
            package_id: null,
            selections: null,
            name: product.name,
            details: variant.name,
            price: Number(variant.price),
            variant_stocks: { [variant.id]: Number(variant.stock) },
            quantity: 1,
            image: getItemImage(product),
        });
    };

    // 2. Selección y Agregado de Paquetes (Bundles)
    const selectPackageVariant = (packageId, productId, variant) => {
        if (!bundleSelections.value[packageId]) {
            bundleSelections.value[packageId] = {};
        }
        bundleSelections.value[packageId][productId] = variant;
    };

    const getSelectedPackageVariant = (packageId, productId) => {
        return bundleSelections.value[packageId]?.[productId] ?? null;
    };

    const isPackageReady = (pkg) => {
        const pkgProducts = getPackageProducts(pkg);
        if (!pkgProducts.length) {
            return false;
        }

        const selectedMap = bundleSelections.value[pkg.id] ?? {};

        return pkgProducts.every((product) => {
            const chosenVariant = selectedMap[product.id];
            return chosenVariant && availableStock(chosenVariant) > 0;
        });
    };

    const addPackageToCart = (pkg) => {
        if (!isPackageReady(pkg)) {
            error422('Selecciona una variante con stock disponible para cada prenda del paquete.');
            return;
        }

        const pkgProducts = getPackageProducts(pkg);
        const selectedMap = bundleSelections.value[pkg.id];

        const selectionsPayload = {};
        const variantStocks = {};
        const detailsParts = [];

        for (const product of pkgProducts) {
            const chosenVariant = selectedMap[product.id];
            selectionsPayload[product.id] = chosenVariant.id;
            variantStocks[chosenVariant.id] = Number(chosenVariant.stock);
            detailsParts.push(`${product.name}: ${chosenVariant.name}`);
        }

        const uniqueKey = `bundle-${pkg.id}-${JSON.stringify(selectionsPayload)}`;
        const existing = cartItems.value.find((item) => item.uniqueKey === uniqueKey);

        if (existing) {
            existing.quantity += 1;
        } else {
            cartItems.value.push({
                uniqueKey,
                type: 'bundle',
                variant_id: null,
                product_id: null,
                package_id: pkg.id,
                selections: selectionsPayload,
                name: `Paquete: ${pkg.name}`,
                details: detailsParts.join(' · '),
                price: Number(pkg.price ?? pkg.display_price ?? 0),
                variant_stocks: variantStocks,
                quantity: 1,
                image: getItemImage(pkg),
            });
        }

        // Limpiamos selección si alguna de las variantes elegidas ya quedó en 0 disponible
        for (const product of pkgProducts) {
            const chosenVariant = selectedMap[product.id];
            if (availableStock(chosenVariant) <= 0) {
                delete bundleSelections.value[pkg.id][product.id];
            }
        }
    };

    // Verifica si se puede sumar +1 a un item del ticket (respetando stock compartido entre variantes y paquetes)
    const canIncrementItem = (cartItem) => {
        if (cartItem.type === 'variant') {
            const maxStock = cartItem.variant_stocks?.[cartItem.variant_id] ?? 0;
            return getCartQuantity(cartItem.variant_id) < maxStock;
        }

        if (cartItem.type === 'bundle' && cartItem.selections) {
            return Object.values(cartItem.selections).every((variantId) => {
                const maxStock = cartItem.variant_stocks?.[variantId] ?? 0;
                return getCartQuantity(variantId) < maxStock;
            });
        }

        return false;
    };

    const incrementQuantity = (uniqueKey) => {
        const item = cartItems.value.find((i) => i.uniqueKey === uniqueKey);
        if (item && canIncrementItem(item)) {
            item.quantity += 1;
        }
    };

    const decrementQuantity = (uniqueKey) => {
        const index = cartItems.value.findIndex((i) => i.uniqueKey === uniqueKey);
        if (index === -1) {
            return;
        }

        if (cartItems.value[index].quantity > 1) {
            cartItems.value[index].quantity -= 1;
        } else {
            cartItems.value.splice(index, 1);
        }
    };

    const removeItem = (uniqueKey) => {
        cartItems.value = cartItems.value.filter((i) => i.uniqueKey !== uniqueKey);
    };

    const clearCart = () => {
        cartItems.value = [];
    };

    const totalItemsCount = computed(() =>
        cartItems.value.reduce((acc, item) => acc + item.quantity, 0)
    );

    const totalAmount = computed(() =>
        cartItems.value.reduce((acc, item) => acc + item.price * item.quantity, 0)
    );

    const canCheckout = computed(() => {
        return (
            cartItems.value.length > 0 &&
            form.email.trim().length > 0 &&
            !form.processing
        );
    });

    const submitSale = () => {
        if (!cartItems.value.length) {
            error422('Agrega al menos un producto o paquete al ticket antes de cobrar.');
            return;
        }

        if (!form.email.trim()) {
            error422('Ingresa el correo del cliente para enviarle su recibo.');
            return;
        }

        form.items = cartItems.value.map((item) => ({
            type: item.type,
            quantity: item.quantity,
            variant_id: item.type === 'variant' ? item.variant_id : null,
            package_id: item.type === 'bundle' ? item.package_id : null,
            selections: item.type === 'bundle' ? item.selections : null,
        }));

        form.post(route('admin.pos.store'), {
            preserveScroll: true,
            onSuccess: () => {
                cartItems.value = [];
                bundleSelections.value = {};
                form.reset('email', 'customer_name', 'items');
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                if (firstError) {
                    error422(firstError);
                }
            },
        });
    };

    return {
        activeTab,
        searchQuery,
        currentPage,
        totalPages,
        paginatedItems,
        productsCount: computed(() => filteredProducts.value.length),
        packagesCount: computed(() => filteredPackages.value.length),
        cartItems,
        form,
        getVariants,
        getPackageProducts,
        getItemImage,
        getCartQuantity,
        availableStock,
        addVariantToCart,
        selectPackageVariant,
        getSelectedPackageVariant,
        isPackageReady,
        addPackageToCart,
        canIncrementItem,
        incrementQuantity,
        decrementQuantity,
        removeItem,
        clearCart,
        goToPage,
        totalItemsCount,
        totalAmount,
        canCheckout,
        submitSale,
    };
}
