



function toggleCart() { //toggle the cart
    const cartModal = document.querySelector('.cart-modal');
    cartModal.classList.toggle('active');

}
function toggleCheckout() { //show checkout form and hide the cart items
    const modal = document.getElementById('cartModal');
    if (modal.style.display === 'block') {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 400); // wait for animation to finish before hiding
    } else {
        modal.style.display = 'block';
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
    }

}
function selectPaymentMethod(method) { // select payment method in checkout form
    currentPaymentMethod = method;
    document.querySelectorAll('.payment-method').forEach(btn => btn.classList.remove('selected'));
    event.target.classList.add('active');

    const creditCartForm = document.getElementById('creditCardForm');
    const paypalForm = document.getElementById('paypalForm');
    if (method === 'credit') {
        creditCartForm.style.display = 'block';
    } else {
        creditCartForm.style.display = 'none';
    }
}
const products = [{
    id: 1,
    title: "Premium IAU T-Shirt",
    category: "clothes",
    price: 59.99,
    image: "/assets/img/tshirt.webp",
},
{
    id: 2,
    title: "Premium IAU Hoodie",
    category: "clothes",
    price: 59.99,
    image: "/assets/img/tshirt.webp",
},
{
    id: 3,
    title: "Premium IAU Cap",
    category: "accessories",
    price: 59.99,
    image: "/assets/img/tshirt.webp",
},
{
    id: 4,
    title: "Premium IAU Mug",
    category: "souvenirs",
    price: 59.99,
    image: "/assets/img/tshirt.webp",
}];
let cart = []; // shopping cart array
let currentPaymentMethod = 'credit'; // current selected payment method

function renderProducts(productToRender = products) { /* shopping cart templates - DO NOT EDIT THIS PLEASE !!!!!!  */
    const grid = document.getElementById('productGrid');
    grid.innerHTML = productToRender.map(product => ` <div class="product-card" data-category="${product.category}">
             <img src="${product.image}" alt="${product.title}"><!-- product image -->
             <div class="product-info">
                <h3 class = "product-title">${product.title}</h3>
                <p class="product-category">${product.category.charAt(0).toUpperCase() + product.category.slice(1)}</p>
                <div class="product-price">${product.price} SAR</div>
             </div>
                <button class="add-to-cart" onclick="addToCart(${product.id})">Add to Cart</button>
                </div>`).join(''); // clear existing products
}// render products to the page
function filterProducts(category) { // filter products by category!!!!
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active')); // remove active class from all buttons
    event.target.classList.add('active');

    const filterProducts = category === 'all' ? products : products.filter(p => p.category === category); // filter products based on category
    renderProducts(filterProducts); // render filtered products
}

function addToCart(productId) { // add product to cart
    const product = products.find(p => p.id === productId);
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += 1; // increase that quantityyyy
    } else {
        cart.push({ ...product, quantity: 1 }); // add new item to cart
    }
    updateCartCount();
    renderCartItems(); // update cart display

    const button = event.target;
    const text = button.textContent;
    button.innerHTML = `<i class="fas fa-check"></i> Added!`;
    button.style.background = "#28a745"; // change button color to green
    setTimeout(() => { // reset button after 1 seconds
        button.innerHTML = text;
        button.style.background = ""; // reset button color
    }, 1000); // disable the button after adding to cart
}
function removeFromCart(productId) { // remove product from cart
    cart = cart.filter(item => item.id !== productId);
    updateCartCount();
    renderCartItems(); // update cart display
}
function updateQuantity(productId, change) { // update quantity of a cart item
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(productId); // remove item if quantity is 0 or less
        } else {
            updateCartCount(); // update cart display
            renderCartItems();
        }
    }

}
function updateCartCount() { // update cart item count in the header
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cartCount').textContent = count;
}
function renderCartItems() { // render cart items in the cart modal
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');

    if (cart.length === 0) { // EMPTY! DONT SHOW ANYTHING
        cartItems.innerHTML = `<div class="empty-cart">
                        <div class="empty-cart-logo"><i class="fa fa-shopping-cart"></i></div>
                        <p>Your cart is empty</p>
                    </div>
                    `;
        cartTotal.style.display = 'none'; // hide total price
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
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('totalPrice').textContent = `Total: ${total.toFixed(2)}SAR`;
    document.getElementById('checkoutTotal').textContent = `Total: ${total.toFixed(2)}SAR`;
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

function processOrder(event) { // process the order when checkout form is submitted
    event.preventDefault();

    if (cart.length === 0) {
        return;
    }
    // by now here should have the process to the database,,, ;ppp
    const formData = new FormData(event.target);
    const orderData = {
        item: cart,
        total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
        customer: {
            name: formData.get('name'),
            email: formData.get('email'),
            mobile: formData.get('mobile')
        },
        shipping: {
            address: formData.get('address'),
            address2: formData.get('address2'),
            city: formData.get('city'),
            zip: formData.get('zip'),
            state: formData.get('state'),
            country: formData.get('country')
        },
        payment: {
            method: currentPaymentMethod,
            cardNumber: formData.get('cardNumber'),
            expiry: formData.get('expiry'),
            cvv: formData.get('cvv')
        }
    };
    const placeOrderBtn = document.querySelector('.place-order-btn');
    placeOrderBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Processing...`;
    placeOrderBtn.disabled = true; // disable the button to prevent multiple submissions
    setTimeout(() => {
        alert('Order placed successfully!');

        cart = []; // clear the cart
        updateCartCount();
        renderCartItems(); // update cart display
        toggleCart();
        event.target.reset();
        placeOrderBtn.innerHTML = '<i class="fas fa-check"></i> Place Order';
        placeOrderBtn.disabled = false;
    }, 2000); // simulate order processing time
}
document.addEventListener('DOMContentLoaded', function () { // render products on page load
    renderProducts();
    updateCartCount();
});

document.addEventListener('DOMContentLoaded', function () { // render the events n everything now!!
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

document.addEventListener('DOMContentLoaded', function () { // Click outside of the cart to leave
    const cartModal = document.getElementById('cart-modal');
    if (cartModal) {
        cartModal.addEventListener('click', function (e) {
            if (e.target === this) {
                toggleCart();
            }
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) { // smooth scrolling
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


