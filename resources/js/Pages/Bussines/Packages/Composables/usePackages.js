


export const usePackages = (products = {}) => {
    
    // suma de precios de productos
    const calcProdPrice = (products) => {
        let totalPrice = 0;
        products.forEach(product => {
            totalPrice += parseInt(product.price);
        });
        return totalPrice;
    };

    return {
        calcProdPrice
    };
}