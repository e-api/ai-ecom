/**
 * Cart Module - Update (+/- buttons), Delete, Add to Cart, Cart Count
 */
$(document).ready(function() {
    console.log('Cart JS loaded');

    // Plus button
    $(document).on('click', '.cartQtyPlus', function() {
        var $btn = $(this);
        var $input = $btn.siblings('.cartQty');
        var currentVal = parseInt($input.val(), 10) || 1;
        $input.val(currentVal + 1);
        updateCartItem($input);
    });

    // Minus button - if quantity would go to 0, delete the item instead
    $(document).on('click', '.cartQtyMinus', function() {
        var $btn = $(this);
        var $input = $btn.siblings('.cartQty');
        var currentVal = parseInt($input.val(), 10) || 1;
        if (currentVal <= 1) {
            var $row = $btn.closest('tr');
            var $removeBtn = $row.find('.removeCartItem');
            if ($removeBtn.length) {
                deleteCartItem($removeBtn);
            }
        } else {
            $input.val(currentVal - 1);
            updateCartItem($input);
        }
    });

    // Manual input change
    $(document).on('change', '.cartQty', function() {
        var $input = $(this);
        var val = parseInt($input.val(), 10);
        if (isNaN(val) || val < 1) {
            $input.val(1);
        }
        updateCartItem($input);
    });

    // Remove item
    $(document).on('click', '.removeCartItem', function() {
        var $btn = $(this);
        deleteCartItem($btn);
    });

    /**
     * Update cart item quantity via AJAX
     */
    function updateCartItem($input) {
        var quantity = parseInt($input.val(), 10);
        if (isNaN(quantity) || quantity < 1) {
            $input.val(1);
            quantity = 1;
        }

        var itemId = $input.data('id');
        var $row = $input.closest('tr');
        var $subtotalCell = $row.find('.subTotal');

        console.log('Updating item:', itemId, 'quantity:', quantity);

        $.ajax({
            url: '/cart/update',
            type: 'GET',
            data: {
                id: itemId,
                quantity: quantity
            },
            dataType: 'json',
            beforeSend: function() {
                $input.prop('disabled', true);
                $row.find('.cartQtyBtn').prop('disabled', true);
            },
            success: function(response) {
                console.log('Update response:', response);
                if (response.status) {
                    $subtotalCell.text(response.subtotal);
                    updateTotals(response.cartTotal);
                    refreshCartCount(response.cartCount);
                } else {
                    if (typeof Toast !== 'undefined') {
                        Toast.error(response.message || 'Failed to update cart.');
                    }
                }
            },
            error: function(xhr) {
                console.log('Update error:', xhr.responseText);
                var msg = 'An error occurred while updating the cart.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                if (typeof Toast !== 'undefined') {
                    Toast.error(msg);
                }
            },
            complete: function() {
                $input.prop('disabled', false);
                $row.find('.cartQtyBtn').prop('disabled', false);
            }
        });
    }

    /**
     * Delete cart item via AJAX
     */
    function deleteCartItem($btn) {
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
            return;
        }

        var itemId = $btn.data('id');
        var $row = $btn.closest('tr');

        console.log('Deleting item:', itemId);

        $.ajax({
            url: '/cart/delete',
            type: 'POST',
            data: {
                id: itemId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            beforeSend: function() {
                $btn.prop('disabled', true);
                $btn.html('...');
            },
            success: function(response) {
                console.log('Delete response:', response);
                if (response.status) {
                    $row.fadeOut(300, function() {
                        $row.remove();
                        updateTotals(response.cartTotal);
                        refreshCartCount(response.cartCount);
                        if ($('tbody tr').length === 0) {
                            location.reload();
                        }
                    });
                } else {
                    if (typeof Toast !== 'undefined') {
                        Toast.error(response.message || 'Failed to remove item.');
                    }
                    $btn.prop('disabled', false);
                    $btn.html('<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>');
                }
            },
            error: function(xhr) {
                console.log('Delete error:', xhr.responseText);
                var msg = 'An error occurred while removing the item.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                if (typeof Toast !== 'undefined') {
                    Toast.error(msg);
                }
                $btn.prop('disabled', false);
                $btn.html('<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>');
            }
        });
    }

    /**
     * Update grand total displays
     */
    function updateTotals(cartTotal) {
        $('.grandTotalValue').text('$' + cartTotal);
    }

    /**
     * Refresh cart count in header/nav (from response data)
     */
    function refreshCartCount(count) {
        $('[data-cart-count]').text(count);
        if (count === 0) {
            $('[data-cart-count]').addClass('hidden');
        } else {
            $('[data-cart-count]').removeClass('hidden');
        }
    }

    /**
     * Get CSRF token from meta tag
     */
    function getCsrfToken() {
        return $('meta[name="csrf-token"]').attr('content') || '';
    }

    // ============================================================
    // ADD TO CART - Home page (add-to-cart-btn)
    // ============================================================
    $(document).on('click', '.add-to-cart-btn', function() {
        var button = $(this);
        var product_id = button.data('product-id');
        var originalText = button.html();

        if (!product_id) {
            if (typeof Toast !== 'undefined') {
                Toast.error('Product is missing. Please refresh the page and try again.');
            } else {
                alert('Product is missing. Please refresh the page and try again.');
            }
            return false;
        }

        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                _token: getCsrfToken(),
                product_id: product_id,
                variant_id: null,
                quantity: 1
            },
            beforeSend: function() {
                button.prop('disabled', true).text('Adding...');
            },
            success: function(response) {
                if (response.status) {
                    // Update cart count badge
                    var count = response.cartCount || 0;
                    $('[data-cart-count]').text(count);
                    if (count === 0) {
                        $('[data-cart-count]').addClass('hidden');
                    } else {
                        $('[data-cart-count]').removeClass('hidden');
                    }
                    if (typeof Toast !== 'undefined') {
                        Toast.success(response.message || 'Product added to cart successfully.');
                    } else {
                        alert(response.message || 'Product added to cart successfully.');
                    }
                } else {
                    if (typeof Toast !== 'undefined') {
                        Toast.error(response.message || 'Something went wrong.');
                    } else {
                        alert(response.message || 'Something went wrong.');
                    }
                    fetchCartCount();
                }
            },
            error: function(xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong. Please try again.';
                if (typeof Toast !== 'undefined') {
                    Toast.error(message);
                } else {
                    alert(message);
                }
            },
            complete: function() {
                button.prop('disabled', false).html(originalText || 'Add');
            }
        });
    });

    // ============================================================
    // ADD TO CART - Detail page (#addToCartBtn)
    // ============================================================
    $(document).on('click', '#addToCartBtn', function() {
        var button = $(this);
        var product_id = $('#product_id').val();
        var variantInput = $('#variant_id');
        var variant_id = variantInput.length ? variantInput.val() : null;
        var quantity = parseInt($('#quantity').val(), 10);
        var originalText = button.html();

        if (!product_id) {
            if (typeof Toast !== 'undefined') {
                Toast.error('Product is missing. Please refresh the page and try again.');
            } else {
                alert('Product is missing. Please refresh the page and try again.');
            }
            return false;
        }

        if (variantInput.length && variant_id === '') {
            if (typeof Toast !== 'undefined') {
                Toast.error('Please select size.');
            } else {
                alert('Please select size.');
            }
            return false;
        }

        if (!quantity || quantity < 1) {
            if (typeof Toast !== 'undefined') {
                Toast.error('Please enter a valid quantity.');
            } else {
                alert('Please enter a valid quantity.');
            }
            return false;
        }

        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                _token: getCsrfToken(),
                product_id: product_id,
                variant_id: variant_id,
                quantity: quantity
            },
            beforeSend: function() {
                button.prop('disabled', true).text('Adding...');
            },
            success: function(response) {
                if (response.status) {
                    // Update cart count badge
                    var count = response.cartCount || 0;
                    $('[data-cart-count]').text(count);
                    if (count === 0) {
                        $('[data-cart-count]').addClass('hidden');
                    } else {
                        $('[data-cart-count]').removeClass('hidden');
                    }
                    if (typeof Toast !== 'undefined') {
                        Toast.success(response.message || 'Product added to cart successfully.');
                    } else {
                        alert(response.message || 'Product added to cart successfully.');
                    }
                } else {
                    if (typeof Toast !== 'undefined') {
                        Toast.error(response.message || 'Something went wrong.');
                    } else {
                        alert(response.message || 'Something went wrong.');
                    }
                    fetchCartCount();
                }
            },
            error: function(xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong. Please try again.';
                if (typeof Toast !== 'undefined') {
                    Toast.error(message);
                } else {
                    alert(message);
                }
            },
            complete: function() {
                button.prop('disabled', false).html(originalText || 'Add to Cart');
            }
        });
    });

    // ============================================================
    // Fetch cart count from server
    // ============================================================
    function fetchCartCount() {
        $.ajax({
            url: '/cart/count',
            type: 'GET',
            success: function(response) {
                $('[data-cart-count]').text(response.count ?? 0);
            }
        });
    }
});