import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { error422 } from '@/lib/alerts';
import { useLoading, useSending } from '@/Hooks/useLoading';
import { usePage } from '@inertiajs/vue3';
import { useCartStore } from '@/./Stores/useCartStore';
import { useCheckoutinfoStore } from '@/./Stores/useCheckoutinfo';
import axios from 'axios';

export const useCheckout = () => {
    const cartStore = useCartStore();
    const checkoutInfoStore = useCheckoutinfoStore();
    const page = usePage();
    const { isLoading, setLoading } = useLoading();
    const { isSending, setSending } = useSending();

    // Manejo de Sesión (Si existe user, está logueado)
    const user = computed(() => page?.props?.auth?.user);


    // Formularios
    const form = useForm({
        email: user?.value?.email ?? checkoutInfoStore.email,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        create_account: checkoutInfoStore.create_account,
        password: null,
        password_confirmation: null,
        payment_method: checkoutInfoStore.payment_method,
        coupon_code_id: checkoutInfoStore.coupon_code_id,
        shipping: {
            name: user?.value?.name ?? checkoutInfoStore.shipping.name,
            lastname: user?.value?.lastname ?? checkoutInfoStore.shipping.lastname,
            street: checkoutInfoStore.shipping.street,
            outdoor_number: checkoutInfoStore.shipping.outdoor_number,
            indoor_number: checkoutInfoStore.shipping.indoor_number,
            colony: checkoutInfoStore.shipping.colony,
            state: checkoutInfoStore.shipping.state,
            city: checkoutInfoStore.shipping.city,
            zip: checkoutInfoStore.shipping.zip,
            phone: checkoutInfoStore.shipping.phone
        },
        cart_items: cartStore.items.map(item => ({
            uniqueKey: item.uniqueKey,
            type: item.type,
            backend_payload: item.backend_payload,
            quantity: item.quantity
        })),
        terms_accepted: false
    });

    const paymentOptions = [
        { id: 1, label: 'Efectivo (Pagar al recoger)' },
        { id: 2, label: 'Tarjeta de Crédito / Débito (Stripe)' }
    ];

    // Lógica de Cupón
    const couponCode = ref('');
    const appliedCoupon = ref(checkoutInfoStore.applied_coupon);
    const couponError = ref('');
    const isApplyingCoupon = ref(false);

    const persistCheckoutInfo = () => {
        checkoutInfoStore.$patch({
            email: form.email,
            create_account: form.create_account,
            payment_method: form.payment_method,
            coupon_code_id: form.coupon_code_id,
            applied_coupon: appliedCoupon.value,
            shipping: { ...form.shipping },
        });
    };

    watch(form, persistCheckoutInfo, { deep: true });
    watch(appliedCoupon, persistCheckoutInfo, { deep: true });
    persistCheckoutInfo();
    const applyCoupon = async () => {
        couponError.value = '';
        if (!couponCode.value) return;

        setLoading(true);
        isApplyingCoupon.value = true;
        try {
            const response = await axios.post(route('apply.coupon'), {
                code: couponCode.value,
                cart_total: cartStore.totalAmount
            });

            if (response.data.success) {
                appliedCoupon.value = {
                    id: response.data.id,
                    code: response.data.code,
                    discount_type: response.data.discount_type,
                    discount: response.data.discount
                };
                form.coupon_code_id = appliedCoupon.value.id;
                couponCode.value = '';
            }
        } catch (error) {
            couponError.value = error.response?.data?.message || 'Error al aplicar el cupón';
            error422("Error al aplicar el cupón", couponError.value);
        } finally {
            isApplyingCoupon.value = false;
            setLoading(false);
        }
    };

    const getDiscountAmount = () => {
        if (!appliedCoupon.value) return '';
        if (appliedCoupon.value.discount_type === 'percentage') {
            let discountAmount = (cartStore.totalAmount * appliedCoupon.value.discount) / 100;
            return `-$${discountAmount.toFixed(2)} `;
        } else if (appliedCoupon.value.discount_type === 'fixed') {
            const discountAmount = Math.min(Number(appliedCoupon.value.discount), cartStore.totalAmount);
            return `-$${discountAmount.toFixed(2)}`;
        }
        return '';
    };

    const removeCoupon = () => {
        appliedCoupon.value = null;
        form.coupon_code_id = null;
    };

    // Cálculos Finales
    const shippingCost = ref(150); // Puedes hacerlo dinámico después

    const getDiscounts = computed(() => {
        // calculamos el descuento basado en los compared_price de cada item
        return cartStore.items.reduce((totalDiscount, item) => {
            if (item.compared_price && item.compared_price > item.price) {
                const discountPerItem = item.compared_price - item.price;
                return totalDiscount + (discountPerItem * item.quantity);
            }
            return totalDiscount;
        }, 0).toFixed(2);
    });

    const subtotal = computed(() => {
        return cartStore.items.reduce((total, item) => {
            const priceToUse = item.compared_price ?? item.price;
            return total + (priceToUse * item.quantity);
        }, 0).toFixed(2);
    });

    const finalTotal = computed(() => {
        /* Aun no aplica porque el shipping aun no esta disponible, pero lo dejamos para cuando si lo este
        let total = cartStore.totalAmount + shippingCost.value;
        if (appliedCoupon.value) {
            total -= appliedCoupon.discount;
        }*/
        let total = cartStore.totalAmount;

        if (appliedCoupon.value) {
            if (appliedCoupon.value.discount_type === 'percentage') {
                total -= (total * appliedCoupon.value.discount) / 100;
            } else if (appliedCoupon.value.discount_type === 'fixed') {
                total -= appliedCoupon.value.discount;
            }
        }
        return (Math.max(0, total)).toFixed(2); // Evita totales negativos
    });

    const removeUnavailableCartItem = (errors) => {
        if (errors.affected_item_key && errors.available_quantity !== undefined) {
            const item = cartStore.items.find(i => i.uniqueKey === errors.affected_item_key);
            if (item) {
                item.quantity = errors.available_quantity;
                if (item.quantity === 0) cartStore.removeItem(item.uniqueKey);
            }
        }

        if (cartStore.items.length === 0) {
            setTimeout(() => {
                window.location.href = route('collection.index');
            }, 2500);
        }
    };

    const submitCheckout = () => {
        if (form.processing || cartStore.items.length === 0) {
            return;
        }

        // Aquí mandaremos el form.value y el cartStore.items a Laravel
        if (!form.create_account && form.password) {
            form.password = null;
            form.password_confirmation = null;
        }
        setSending(true);

        form.post(route('checkout.store'), {
            preserveScroll: true,
            onSuccess: (page) => {
                const stripeUrl = page.props.flash?.stripe_url ?? null;

                if (stripeUrl) {
                    window.location.assign(stripeUrl);
                }
            },
            onError: (errors) => {

                // Si Laravel nos devuelve un error de stock formateado
                if (errors.stock_error) {
                    // Muestra un mensaje gigante
                    error422("Error de Stock", errors.stock_error);

                    // Si Laravel nos dice cuánto queda exactamente, actualizamos Pinia
                    removeUnavailableCartItem(errors);
                } else if (errors.order_limit) {
                    error422("Límite de compra alcanzado", errors.order_limit);
                    removeUnavailableCartItem(errors);
                    form.errors = errors;
                } else if (errors.coupon_error) {
                    error422("Cupón no disponible", errors.coupon_error);
                    removeCoupon();
                    form.errors = errors;
                } else {
                    // Errores de validación estándar (email, vacíos, etc)
                    // Se asignan al NeoInput automáticamente a través de form.error
                    form.errors = errors;
                }
            },
            onFinish: () => {
                setSending(false);
            }
        });
    };

    return {
        isSending,
        isLoading,
        form,
        user,
        cartStore,
        paymentOptions,
        couponCode,
        appliedCoupon,
        couponError,
        isApplyingCoupon,
        applyCoupon,
        removeCoupon,
        getDiscountAmount,
        shippingCost,
        subtotal,
        getDiscounts,
        finalTotal,
        submitCheckout
    };
}