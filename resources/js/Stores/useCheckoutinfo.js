import { defineStore } from 'pinia';

export const useCheckoutinfoStore = defineStore('checkoutinfo', {
    state: () => ({
        email: null,
        create_account: false,
        payment_method: 1,
        coupon_code_id: null,
        applied_coupon: null,
        shipping: {
            name: null,
            lastname: null,
            street: null,
            outdoor_number: null,
            indoor_number: null,
            colony: null,
            state: null,
            city: null,
            zip: null,
            phone: null
        },
    }),
    actions: {
        clearCheckoutInfo() {
            this.$reset();
        },
    },
    persist: true,
});