import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    // 1. STATE: La base de datos temporal
    state: () => ({
        items: [],
    }),

    // 2. GETTERS: Cálculos automáticos (Reactivos)
    getters: {
        totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
        totalAmount: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0),
    },

    // 3. ACTIONS: Los métodos para modificar el carrito
    actions: {
        // A) Para productos individuales (Viene de ProductShow)
        addVariant(product, variant, quantity = 1) {
            // Creamos una llave única para saber si ya existe en el carrito y solo sumar cantidad
            const uniqueKey = `variant-${variant.id}`;
            const slug = product.slug;
            const existing = this.items.find(i => i.uniqueKey === uniqueKey);
            
            if (existing) {
                existing.quantity += quantity;
            } else {
                this.items.push({
                    uniqueKey,
                    type: 'variant',
                    slug,
                    name: product.name,
                    details: variant.name, // Ej: "Talla M - Negra"
                    compared_price: variant.compare_at_price,
                    price: variant.price,
                    image: product.photos?.[0]?.url || '/placeholder.png',
                    quantity,
                    limit_per_user: product.limit_per_user,
                    backend_payload: { type: 'variant', id: variant.id } 
                });
            }
        },
        
        // B) Para paquetes armados (Viene de PackageShow)
        addBundle(pack, selections, quantity = 1) {
            // JSON.stringify asegura que si arma el MISMO combo 2 veces, se sume la cantidad
            const uniqueKey = `bundle-${pack.id}-${JSON.stringify(selections)}`;
            const slug = pack.slug;
            const existing = this.items.find(i => i.uniqueKey === uniqueKey);
            
            if (existing) {
                existing.quantity += quantity;
            } else {
                this.items.push({
                    uniqueKey,
                    slug,
                    type: 'bundle',
                    name: pack.name,
                    details: 'Kit Personalizado', // Más adelante podemos mapear los nombres exactos aquí
                    compared_price: pack.compare_at_price,
                    price: pack.display_price,
                    image: pack.photos?.[0]?.url || '/placeholder.png',
                    quantity,
                    limit_per_user: pack.limit_per_user,
                    backend_payload: { type: 'bundle', id: pack.id, selections } 
                });
            }
        },
        
        removeItem(uniqueKey) {
            this.items = this.items.filter(item => item.uniqueKey !== uniqueKey);
        },
        
        clearCart() {
            this.items = [];
        }
    },
    // Esto activa pinia-plugin-persistedstate (Se guarda en LocalStorage automáticamente)
    persist: true 
});