// POS System JavaScript

class POSSystem {
    constructor() {
        this.cart = [];
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.updateCartDisplay();
    }
    
    bindEvents() {
        // Product click
        $(document).on('click', '.product-card:not(.out-of-stock)', (e) => {
            const productData = $(e.currentTarget).data('product');
            this.addToCart(productData);
        });
        
        // Search product
        $('#search-product').on('input', (e) => {
            this.searchProducts(e.target.value);
        });
        
        // Filter by category
        $('#filter-category').on('change', (e) => {
            this.filterByCategory(e.target.value);
        });
        
        // Cart quantity change
        $(document).on('change', '.cart-quantity', (e) => {
            const index = $(e.target).data('index');
            const quantity = parseInt(e.target.value);
            this.updateQuantity(index, quantity);
        });
        
        // Remove from cart
        $(document).on('click', '.remove-item', (e) => {
            const index = $(e.target).data('index');
            this.removeFromCart(index);
        });
        
        // Clear cart
        $('#btn-clear-cart').on('click', () => {
            this.clearCart();
        });
        
        // Discount change
        $('#discount-percent').on('input', () => {
            this.updateCartDisplay();
        });
        
        // Payment amount change
        $('#paid-amount').on('input', () => {
            this.calculateChange();
        });
        
        // Payment method change
        $('#payment-method').on('change', (e) => {
            if (e.target.value === 'cash') {
                $('#cash-payment').show();
            } else {
                $('#cash-payment').hide();
                $('#paid-amount').val(this.getTotal());
                this.calculateChange();
            }
        });
        
        // Checkout
        $('#btn-checkout').on('click', () => {
            this.checkout();
        });
        
        // Barcode scanner simulation (Enter key)
        $('#search-product').on('keypress', (e) => {
            if (e.which === 13) {
                this.scanBarcode(e.target.value);
            }
        });
    }
    
    addToCart(product) {
        const existingIndex = this.cart.findIndex(item => item.id === product.id);
        
        if (existingIndex >= 0) {
            this.cart[existingIndex].quantity++;
        } else {
            this.cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
                stock: product.stock
            });
        }
        
        this.updateCartDisplay();
        this.showToast('Product added to cart', 'success');
    }
    
    updateQuantity(index, quantity) {
        if (quantity <= 0) {
            this.removeFromCart(index);
            return;
        }
        
        const item = this.cart[index];
        if (quantity > item.stock) {
            this.showToast('Insufficient stock', 'error');
            return;
        }
        
        this.cart[index].quantity = quantity;
        this.updateCartDisplay();
    }
    
    removeFromCart(index) {
        this.cart.splice(index, 1);
        this.updateCartDisplay();
    }
    
    clearCart() {
        this.cart = [];
        this.updateCartDisplay();
    }
    
    updateCartDisplay() {
        const cartContainer = $('#cart-items');
        
        if (this.cart.length === 0) {
            cartContainer.html('<p class="text-center text-muted py-5">Cart is empty</p>');
            $('#btn-checkout').prop('disabled', true);
            return;
        }
        
        let html = '';
        this.cart.forEach((item, index) => {
            html += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <div class="d-flex align-items-center">
                                <input type="number" class="form-control form-control-sm cart-quantity me-2" 
                                       style="width: 70px;" value="${item.quantity}" min="1" max="${item.stock}"
                                       data-index="${index}">
                                <span class="text-muted">× ${formatCurrency(item.price)}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">${formatCurrency(item.price * item.quantity)}</div>
                            <button class="btn btn-sm btn-outline-danger remove-item" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartContainer.html(html);
        
        // Update totals
        const subtotal = this.getSubtotal();
        const discountPercent = parseFloat($('#discount-percent').val()) || 0;
        const discountAmount = subtotal * (discountPercent / 100);
        const taxAmount = (subtotal - discountAmount) * 0.11;
        const total = subtotal - discountAmount + taxAmount;
        
        $('#cart-subtotal').text(formatCurrency(subtotal));
        $('#cart-tax').text(formatCurrency(taxAmount));
        $('#cart-total').text(formatCurrency(total));
        
        $('#btn-checkout').prop('disabled', false);
        this.calculateChange();
        
        // Trigger cart updated event
        $(document).trigger('cartUpdated');
    }
    
    getSubtotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    }
    
    getTotal() {
        const subtotal = this.getSubtotal();
        const discountPercent = parseFloat($('#discount-percent').val()) || 0;
        const discountAmount = subtotal * (discountPercent / 100);
        const taxAmount = (subtotal - discountAmount) * 0.11;
        return subtotal - discountAmount + taxAmount;
    }
    
    calculateChange() {
        const total = this.getTotal();
        const paid = parseFloat($('#paid-amount').val()) || 0;
        const change = paid - total;
        
        $('#change-amount').text(formatCurrency(Math.max(0, change)));
        
        if (paid >= total) {
            $('#btn-checkout').prop('disabled', false);
        } else {
            $('#btn-checkout').prop('disabled', true);
        }
    }
    
    searchProducts(keyword) {
        keyword = keyword.toLowerCase();
        $('.product-item').each(function() {
            const name = $(this).data('name');
            const barcode = $(this).data('barcode').toLowerCase();
            
            if (name.includes(keyword) || barcode.includes(keyword)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    filterByCategory(categoryId) {
        if (!categoryId) {
            $('.product-item').show();
            return;
        }
        
        $('.product-item').each(function() {
            if ($(this).data('category') == categoryId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    scanBarcode(barcode) {
        // Find product by barcode
        const productCard = $(`.product-item[data-barcode="${barcode}"] .product-card`);
        if (productCard.length) {
            const productData = productCard.data('product');
            this.addToCart(productData);
            $('#search-product').val('');
        } else {
            this.showToast('Product not found', 'error');
        }
    }
    
    checkout() {
        if (this.cart.length === 0) {
            this.showToast('Cart is empty', 'error');
            return;
        }
        
        const total = this.getTotal();
        const paid = parseFloat($('#paid-amount').val()) || total;
        
        if ($('#payment-method').val() === 'cash' && paid < total) {
            this.showToast('Insufficient payment amount', 'error');
            return;
        }
        
        // Prepare checkout data
        const checkoutData = {
            items: this.cart.map(item => ({
                product_id: item.id,
                price: item.price,
                quantity: item.quantity,
                subtotal: item.price * item.quantity
            })),
            customer_id: $('#customer-select').val(),
            payment_method: $('#payment-method').val(),
            paid_amount: paid,
            discount_percent: parseFloat($('#discount-percent').val()) || 0,
            notes: ''
        };
        
        // Show loading
        showLoading();
        
        // Send to server
        $.ajax({
            url: window.baseUrl + 'sales/checkout',
            method: 'POST',
            data: {
                ...checkoutData,
                items: JSON.stringify(checkoutData.items)
            },
            dataType: 'json',
            success: (response) => {
                hideLoading();
                if (response.success) {
                    this.showToast('Transaction completed successfully!', 'success');
                    
                    // Ask for receipt
                    Swal.fire({
                        title: 'Transaction Complete!',
                        text: `Invoice: ${response.invoice_number}`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Print Receipt',
                        cancelButtonText: 'Continue'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(window.baseUrl + 'sales/receipt/' + response.sale_id, '_blank');
                        }
                    });
                    
                    // Clear cart
                    this.clearCart();
                    $('#customer-select').val('').trigger('change');
                    $('#discount-percent').val(0);
                    $('#paid-amount').val('');
                    $('#change-amount').text('Rp 0');
                } else {
                    this.showToast(response.message || 'Transaction failed', 'error');
                }
            },
            error: (xhr, status, error) => {
                hideLoading();
                this.showToast('Transaction failed: ' + error, 'error');
            }
        });
    }
    
    showToast(message, type = 'info') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        
        Toast.fire({
            icon: type === 'error' ? 'error' : type === 'success' ? 'success' : 'info',
            title: message
        });
    }
}

// Initialize POS when document is ready
$(document).ready(function() {
    window.pos = new POSSystem();
    
    // Set base URL for AJAX
    window.baseUrl = 'http://localhost:82/Pos/';
});