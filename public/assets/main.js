// ===================== PAST PURCHASES COOKIE =====================

function savePurchaseToCookie(items, total) {
    const purchase = {
        date: new Date().toLocaleDateString(),
        items: items.map(i => ({ title: i.title, qty: i.quantity, price: i.price })),
        total: total,
    };
    let history = getPastPurchases();
    history.unshift(purchase);
    if (history.length > 5) history = history.slice(0, 5);
    const expiry = new Date();
    expiry.setDate(expiry.getDate() + 30);
    document.cookie = `past_purchases=${encodeURIComponent(JSON.stringify(history))}; expires=${expiry.toUTCString()}; path=/`;
}

function getPastPurchases() {
    const match = document.cookie.match(/(?:^|;\s*)past_purchases=([^;]+)/);
    if (!match) return [];
    try { return JSON.parse(decodeURIComponent(match[1])); } catch { return []; }
}

function renderPastPurchases() {
    const section = document.getElementById('pastPurchasesSection');
    const list = document.getElementById('pastPurchasesList');
    if (!section || !list) return;
    const past = getPastPurchases();
    if (past.length === 0) { section.style.display = 'none'; return; }
    section.style.display = 'block';
    list.innerHTML = past.map(p => `
        <div class="past-purchase-item">
            <div class="past-purchase-date">${p.date} &mdash; <strong>${p.total.toFixed ? p.total.toFixed(2) : p.total} SAR</strong></div>
            <div class="past-purchase-items">${p.items.map(i => `${i.qty}&times; ${escapeHtml(i.title)}`).join(', ')}</div>
        </div>
    `).join('');
}

function togglePastPurchases() {
    const list = document.getElementById('pastPurchasesList');
    const chevron = document.getElementById('pastPurchasesChevron');
    if (!list) return;
    const open = list.style.display === 'none';
    list.style.display = open ? 'block' : 'none';
    if (chevron) chevron.style.transform = open ? 'rotate(180deg)' : '';
}

function toggleCart() { //toggle the cart
    const cartModal = document.querySelector('.cart-modal');
    cartModal.classList.toggle('active');
    if (cartModal.classList.contains('active')) {
        renderPastPurchases();
    }
}

function selectPaymentMethod(method) { // select payment method in checkout form
    currentPaymentMethod = method;
    document.querySelectorAll('.payment-method').forEach(btn => btn.classList.remove('active'));

    if (event && event.target) {
        const el = event.target.closest('.payment-method');
        if (el) el.classList.add('active');
    }

    const creditCardInfo = document.getElementById('creditCardInfo');
    if (creditCardInfo) {
        if (method === 'creditcard') {
            creditCardInfo.style.display = 'block';
        } else {
            creditCardInfo.style.display = 'none';
        }
    }
}

// products is injected by Blade in each store partial
let cart = [];
let currentPaymentMethod = 'credit';
let appliedDiscount = null; // { code, rate, description }

function renderProducts(productToRender) {
    if (typeof products === 'undefined') return;
    const toRender = productToRender !== undefined ? productToRender : products;
    const grid = document.getElementById('productGrid');
    if (!grid) return;
    if (toRender.length === 0) {
        grid.innerHTML = '<p style="text-align:center;color:#4b4b4b;padding:2rem;">No products found.</p>';
        return;
    }
    grid.innerHTML = toRender.map(product => {
        const desc = product.description
            ? `<p class="product-card-desc">${escapeHtml(product.description.length > 80 ? product.description.substring(0, 80) + '…' : product.description)}</p>`
            : '';

        const hasSizes = Array.isArray(product.size) && product.size.length > 0 && product.size[0] !== '';
        const sizePills = hasSizes
            ? `<div class="product-card-sizes">${product.size.map(s => `<span class="product-card-size-pill">${escapeHtml(s)}</span>`).join('')}</div>`
            : '';

        return `<div class="product-card" data-category="${product.category}" onclick="openProductModal(${product.id})">
            <img src="${product.image}" alt="${escapeHtml(product.title)}">
            <div class="product-info">
                <h3 class="product-title">${escapeHtml(product.title)}</h3>
                <p class="product-category">${product.category.charAt(0).toUpperCase() + product.category.slice(1)}</p>
                ${desc}
                ${sizePills}
                <div class="product-price">${product.price} SAR</div>
            </div>
            <button class="add-to-cart" onclick="event.stopPropagation(); openProductModal(${product.id})">View Details</button>
        </div>`;
    }).join('');
}

let currentSelectedProduct = null;
let currentSelectedSize = null;
let currentReviewRating = 0;

function openProductModal(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    currentSelectedProduct = product;
    currentSelectedSize = null;
    currentReviewRating = 0;

    document.getElementById('modal-product-image').src = product.image;
    document.getElementById('modal-product-image').alt = product.title;
    document.getElementById('modal-product-title').textContent = product.title;
    document.getElementById('modal-product-category').textContent = product.category.charAt(0).toUpperCase() + product.category.slice(1);
    document.getElementById('modal-product-price').textContent = `${product.price} SAR`;
    document.getElementById('modal-product-description').textContent = product.description || '';

    const sizeContainer = document.getElementById('modal-size-container');
    const sizeOptions = document.getElementById('modal-size-options');

    if (product.size && product.size.length > 0 && product.size[0] !== "") {
        sizeContainer.style.display = 'block';
        sizeOptions.innerHTML = product.size.map(s =>
            `<button class="size-btn" onclick="selectProductSize(this, '${s}')">${s}</button>`
        ).join('');
    } else {
        sizeContainer.style.display = 'none';
        currentSelectedSize = 'N/A';
    }

    const addToCartBtn = document.getElementById('modal-add-to-cart');
    addToCartBtn.onclick = function () {
        if (currentSelectedSize === null && product.size && product.size[0] !== "") {
            alert('Please select a size first.');
            return;
        }
        addToCart(product.id);
        const text = addToCartBtn.textContent;
        addToCartBtn.innerHTML = `<i class="fas fa-check"></i> Added!`;
        addToCartBtn.style.background = "#28a745";
        setTimeout(() => {
            addToCartBtn.innerHTML = text;
            addToCartBtn.style.background = "";
            closeProductModal();
        }, 1000);
    };

    // Reset review form if present
    const ratingEl = document.getElementById('starInput');
    if (ratingEl) {
        setRating(0);
    }
    const bodyEl = document.getElementById('reviewBody');
    if (bodyEl) bodyEl.value = '';
    const msgEl = document.getElementById('reviewMsg');
    if (msgEl) msgEl.textContent = '';

    document.getElementById('product-modal').classList.add('active');

    // Load reviews
    loadReviews(productId);
}

function closeProductModal() {
    document.getElementById('product-modal').classList.remove('active');
}

function selectProductSize(btnElement, size) {
    currentSelectedSize = size;
    const buttons = document.getElementById('modal-size-options').querySelectorAll('.size-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    btnElement.classList.add('active');
}

function filterProducts(category) {
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    const filtered = category === 'all' ? products : products.filter(p => p.category === category);
    renderProducts(filtered);
}

function syncCart() {
    if (typeof isAuthenticated !== 'undefined' && isAuthenticated) {
        fetch('/cart/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ cart: cart })
        }).catch(err => console.error("Failed to sync cart:", err));
    }
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    updateCartCount();
    renderCartItems();
    syncCart();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartCount();
    renderCartItems();
    syncCart();
}

function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId);
        } else {
            updateCartCount();
            renderCartItems();
            syncCart();
        }
    }
}

function updateCartCount() {
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    const el = document.getElementById('cartCount');
    if (el) el.textContent = count;
}

function renderCartItems() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');

    if (cart.length === 0) {
        cartItems.innerHTML = `<div class="empty-cart">
                        <div class="empty-cart-logo"><i class="fa fa-shopping-cart"></i></div>
                        <p>Your cart is empty</p>
                    </div>
                    `;
        cartTotal.style.display = 'none';
        return;
    }
    cartItems.innerHTML = cart.map(item => `
    <div class="cart-item">
        <img src="${item.image}" alt="${item.title}" class="cart-item-image">
        <div class="cart-item-info"> <div class="cart-item-title">${item.title}</div>
            <div class="cart-item-price">${(item.price * item.quantity).toFixed(2)}SAR</div>
            <div class="quantity-controls">
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, -1)">−</button>
                <span class="quantity">${item.quantity}</span>
                <button class="quantity-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
            </div>
        </div>
    </div>
`).join('');
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountAmount = appliedDiscount ? subtotal * appliedDiscount.rate : 0;
    const finalTotal = subtotal - discountAmount;

    const totalEl = document.getElementById('totalPrice');
    if (discountAmount > 0) {
        totalEl.innerHTML =
            `<span style="text-decoration:line-through;opacity:0.55;font-size:0.9em">${subtotal.toFixed(2)} SAR</span>` +
            `<span style="color:#2ecc71;font-size:0.8rem;display:block"> ${appliedDiscount.description}</span>` +
            `<strong>${finalTotal.toFixed(2)} SAR</strong>`;
    } else {
        totalEl.textContent = `Total: ${finalTotal.toFixed(2)} SAR`;
    }
    document.getElementById('checkoutTotalPrice').textContent = `Total: ${finalTotal.toFixed(2)} SAR`;
    cartTotal.style.display = 'block';
}

function showCheckout() {
    document.getElementById('cartItems').style.display = 'none';
    document.getElementById('cartTotal').style.display = 'none';
    document.getElementById('checkoutForm').classList.add('active');
}

function hideCheckout() {
    document.getElementById('cartItems').style.display = 'block';
    document.getElementById('cartTotal').style.display = 'block';
    document.getElementById('checkoutForm').classList.remove('active');
}

function applyDiscount() {
    const code = (document.getElementById('discountCodeInput')?.value || '').trim();
    const msgEl = document.getElementById('discountMsg');
    if (!code) { if (msgEl) { msgEl.textContent = 'Enter a discount code.'; msgEl.className = 'discount-msg error'; } return; }

    if (msgEl) { msgEl.textContent = 'Checking…'; msgEl.className = 'discount-msg'; }

    fetch('/discount/apply?code=' + encodeURIComponent(code), {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.valid) {
            appliedDiscount = { code: data.code, rate: data.rate, description: data.description };
            if (msgEl) { msgEl.textContent = 'Applied: ' + data.description; msgEl.className = 'discount-msg success'; }
            renderCartItems();
        } else {
            appliedDiscount = null;
            if (msgEl) { msgEl.textContent = data.message || 'Invalid code.'; msgEl.className = 'discount-msg error'; }
            renderCartItems();
        }
    })
    .catch(() => {
        if (msgEl) { msgEl.textContent = 'Could not validate code.'; msgEl.className = 'discount-msg error'; }
    });
}

function processOrder(event) {
    event.preventDefault();
    if (cart.length === 0) return;

    if (!isAuthenticated) {
        alert('Please log in to place an order.');
        return;
    }

    const formData = new FormData(event.target);
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountAmount = appliedDiscount ? subtotal * appliedDiscount.rate : 0;
    const orderData = {
        items: cart.map(item => ({ id: item.id, quantity: item.quantity })),
        total: parseFloat((subtotal - discountAmount).toFixed(2)),
        discount_code: appliedDiscount ? appliedDiscount.code : null,
        shipping: {
            name:     formData.get('name'),
            email:    formData.get('email'),
            mobile:   formData.get('mobile'),
            address:  formData.get('address'),
            address2: formData.get('address2'),
            city:     formData.get('city'),
            zip:      formData.get('zip'),
            state:    formData.get('state'),
            country:  formData.get('country'),
        },
    };

    const placeOrderBtn = document.querySelector('.place-order-btn');
    placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    placeOrderBtn.disabled = true;

    fetch('/orders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify(orderData),
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw new Error(data.message || 'Order failed.');
        return data;
    })
    .then(data => {
        savePurchaseToCookie([...cart], parseFloat((subtotal - discountAmount).toFixed(2)));
        cart = [];
        syncCart();
        appliedDiscount = null;
        const codeInput = document.getElementById('discountCodeInput');
        if (codeInput) codeInput.value = '';
        const discountMsg = document.getElementById('discountMsg');
        if (discountMsg) { discountMsg.textContent = ''; discountMsg.className = 'discount-msg'; }
        updateCartCount();
        renderCartItems();
        toggleCart();
        event.target.reset();
        placeOrderBtn.innerHTML = '<i class="fas fa-rocket"></i> Place Order';
        placeOrderBtn.disabled = false;
        
        // Redirect to order confirmation/invoice page
        if (data && data.order_id) {
            window.location.href = `/orders/${data.order_id}/invoice`;
        } else {
            alert('Order placed successfully!');
        }
    })
    .catch(err => {
        alert('Error: ' + err.message);
        placeOrderBtn.innerHTML = '<i class="fas fa-rocket"></i> Place Order';
        placeOrderBtn.disabled = false;
    });
}

// ===================== REVIEWS =====================

function loadReviews(productId) {
    const list = document.getElementById('modal-reviews-list');
    const avg = document.getElementById('modal-reviews-avg');
    if (!list) return;
    list.innerHTML = '<div class="reviews-loading">Loading reviews...</div>';
    if (avg) avg.innerHTML = '';

    fetch(`/products/${productId}/reviews`)
        .then(res => res.json())
        .then(reviews => {
            if (reviews.length === 0) {
                list.innerHTML = '<p class="no-reviews">No reviews yet. Be the first to review!</p>';
                return;
            }
            const avgRating = (reviews.reduce((s, r) => s + r.rating, 0) / reviews.length).toFixed(1);
            if (avg) avg.innerHTML = renderStarsDisplay(parseFloat(avgRating)) + ` <span class="avg-score">${avgRating} / 5</span> <span class="avg-count">(${reviews.length} review${reviews.length > 1 ? 's' : ''})</span>`;

            list.innerHTML = reviews.map(r => `
                <div class="review-item">
                    <div class="review-item-header">
                        <span class="review-author">
                            <i class="fas fa-user-circle"></i> ${r.user}
                            ${r.is_verified ? '<span class="verified-badge" style="color: #1a7a3c; font-size: 0.75rem; margin-left: 5px;"><i class="fas fa-check-circle"></i> Verified Purchase</span>' : ''}
                        </span>
                        <span class="review-date">${r.created_at}</span>
                    </div>
                    <div class="review-stars">${renderStarsDisplay(r.rating)}</div>
                    ${r.body ? `<p class="review-body-text">${escapeHtml(r.body)}</p>` : ''}
                </div>
            `).join('');
        })
        .catch(() => {
            list.innerHTML = '<p class="no-reviews">Could not load reviews.</p>';
        });
}

function renderStarsDisplay(rating) {
    let html = '<span class="stars-display">';
    for (let i = 1; i <= 5; i++) {
        html += `<span class="${i <= rating ? 'star-filled' : 'star-empty'}">&#9733;</span>`;
    }
    html += '</span>';
    return html;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

// Star rating input
function setRating(value) {
    currentReviewRating = value;
    const stars = document.querySelectorAll('#starInput .star-btn');
    stars.forEach((s, i) => {
        s.classList.toggle('active', i < value);
    });
    const el = document.getElementById('starInput');
    if (el) el.dataset.rating = value;
}

function submitReview() {
    if (!currentSelectedProduct) return;
    const rating = currentReviewRating;
    const body = document.getElementById('reviewBody').value.trim();
    const msgEl = document.getElementById('reviewMsg');

    if (rating === 0) { msgEl.textContent = 'Please select a star rating.'; msgEl.className = 'review-msg error'; return; }
    if (!body) { msgEl.textContent = 'Please enter a review.'; msgEl.className = 'review-msg error'; return; }

    const btn = document.querySelector('.review-submit-btn');
    btn.disabled = true;
    btn.textContent = 'Submitting...';

    fetch(`/products/${currentSelectedProduct.id}/reviews`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ rating, body }),
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) {
            throw new Error(data.error || 'Something went wrong.');
        }
        return data;
    })
    .then(() => {
        msgEl.textContent = 'Review submitted!';
        msgEl.className = 'review-msg success';
        setRating(0);
        document.getElementById('reviewBody').value = '';
        loadReviews(currentSelectedProduct.id);
    })
    .catch(err => {
        msgEl.textContent = err.message;
        msgEl.className = 'review-msg error';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Submit Review';
    });
}

// ===================== PROFILE =====================

function toggleProfilePopup() {
    const popup = document.getElementById('profilePopup');
    if (popup) popup.classList.toggle('active');
}

function uploadProfilePicture(input) {
    if (!input.files || !input.files[0]) return;
    const formData = new FormData();
    formData.append('profile_picture', input.files[0]);

    fetch(profilePictureUploadUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.url) {
            // Update all avatar image elements on the page
            document.querySelectorAll('#profileAvatarImg, #profilePopupImg').forEach(img => {
                img.src = data.url;
            });
            // If it was previously showing an icon, swap to an img tag
            const iconEl = document.getElementById('profilePopupIcon');
            if (iconEl) {
                const img = document.createElement('img');
                img.src = data.url;
                img.alt = 'Profile';
                img.id = 'profilePopupImg';
                iconEl.replaceWith(img);
            }
            const avatarEl = document.querySelector('.profile-avatar');
            if (avatarEl && !avatarEl.querySelector('img')) {
                avatarEl.innerHTML = `<img src="${data.url}" alt="Profile" id="profileAvatarImg">`;
            }
        }
    })
    .catch(() => alert('Failed to upload profile picture.'));
}

// ===================== DOM READY =====================

document.addEventListener('DOMContentLoaded', function () {
    if (typeof products !== 'undefined') {
        renderProducts();
    }
    updateCartCount();
    renderCartItems();
});

document.addEventListener('DOMContentLoaded', function () {
    const cardNumberInput = document.getElementById('cardNumber');
    const expiryInput = document.getElementById('expiry');
    const cvvInput = document.getElementById('cvv');

    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\s/g, '').replace();
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || '';
            e.target.value = formattedValue;
        });
    }
    if (expiryInput) {
        expiryInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
    if (cvvInput) {
        cvvInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^0-9]/g, '').substring(0, 4);
            e.target.value = value;
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const cartModal = document.getElementById('cart-modal');
    if (cartModal) {
        cartModal.addEventListener('click', function (e) {
            if (e.target === this) toggleCart();
        });
    }

    const productModal = document.getElementById('product-modal');
    if (productModal) {
        productModal.addEventListener('click', function (e) {
            if (e.target === this) closeProductModal();
        });
    }

    // Close profile popup when clicking outside
    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('profileWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const popup = document.getElementById('profilePopup');
            if (popup) popup.classList.remove('active');
        }
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== "#") {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});
