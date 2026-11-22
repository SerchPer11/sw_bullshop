import { ref } from 'vue';
import axios from 'axios';
import { error422 } from '@/lib/alerts';
import { useLoading } from '@/Hooks/useLoading';

export function useCoupon({
    initialCode = '',
    initialAppliedCoupon = null,
    getCartTotal,
    getEmail,
    onApplySuccess,
    onRemove,
    fromPos = false,
} = {}) {
    const { isLoading, setLoading } = useLoading();

    const couponCode = ref(initialCode);
    const appliedCoupon = ref(initialAppliedCoupon);
    const couponError = ref('');
    const isApplyingCoupon = ref(false);

    const applyCoupon = async () => {
        couponError.value = '';
        if (!couponCode.value) return;

        setLoading(true);
        isApplyingCoupon.value = true;

        try {
            const response = await axios.post(route('apply.coupon'), {
                code: couponCode.value,
                cart_total: getCartTotal(),
                email: getEmail ? getEmail() : '',
                from_pos: fromPos,
            });

            if (response.data.success) {
                appliedCoupon.value = {
                    id: response.data.id,
                    code: response.data.code,
                    discount_type: response.data.discount_type,
                    discount: response.data.discount,
                };

                if (onApplySuccess) {
                    onApplySuccess(appliedCoupon.value);
                } else {
                    couponCode.value = '';
                }
            }
        } catch (error) {
            couponError.value = error.response?.data?.message || 'Error al aplicar el cupón';
            error422('Error al aplicar el cupón', couponError.value);
            if (!fromPos) {
                couponCode.value = '';
            }
        } finally {
            isApplyingCoupon.value = false;
            setLoading(false);
        }
    };

    const calculateCouponDiscount = (baseTotal) => {
        const total = Number(baseTotal || 0);
        if (!appliedCoupon.value || total <= 0) return 0;

        if (appliedCoupon.value.discount_type === 'percentage') {
            return (total * Number(appliedCoupon.value.discount)) / 100;
        }
        if (appliedCoupon.value.discount_type === 'fixed') {
            return Math.min(Number(appliedCoupon.value.discount), total);
        }
        return 0;
    };

    const getDiscountAmount = () => {
        if (!appliedCoupon.value) return '';
        const discountAmount = calculateCouponDiscount(getCartTotal());
        if (appliedCoupon.value.discount_type === 'percentage') {
            return `-$${discountAmount.toFixed(2)} `;
        }
        if (appliedCoupon.value.discount_type === 'fixed') {
            return `-$${discountAmount.toFixed(2)}`;
        }
        return '';
    };

    const removeCoupon = () => {
        appliedCoupon.value = null;
        couponError.value = '';
        if (onRemove) {
            onRemove();
        }
    };

    return {
        isLoading,
        setLoading,
        couponCode,
        appliedCoupon,
        couponError,
        isApplyingCoupon,
        applyCoupon,
        removeCoupon,
        calculateCouponDiscount,
        getDiscountAmount,
    };
}
