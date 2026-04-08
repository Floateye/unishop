<x-guest-layout>
    <header class="header"> <!-- header content -->
        <nav class="nav">
            <div class="logo">IAU UniShop</div>
            <ul class="nav-links"><!-- navigation links -->
                <li><a href="#home">Home</a></li>
                <li><a href="#shop">Shop</a></li>
                <li><a href="#aboutus">About us!</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="nav-action">
                <button class="cart-btn" onclick="toggleCart()">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="cart-count" id="cartCount">0</span>
                    Cart
                </button>
            </div>
        </nav>
    </header>
    <!-- hero -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1>IAU UniShop</h1>
            <p>The Official Imam Abdulrahman bin Faisal University</p>
            <p> Merchandise Store</p>
            <a href="#products" class="shop-btn">Collection</a>
        </div>
    </section>
    <!-- products -->
    <section class="products" id="products">
        <div class="section-title">
            <h2>Featured Products</h2>
            <p>Discover our latest and most popular items.</p>
        </div>
        <div class="filters"> <!-- product filters -->
            <button class="filter-btn active" onclick="filterProducts('all')"><i class="fa fa-filter"></i> All
                items</button>
            <button class="filter-btn" onclick="filterProducts('clothes')"><i class="fa fa-tshirt"></i> Clothes</button>
            <button class="filter-btn" onclick="filterProducts('accessories')"><i class="fa fa-hat-wizard"></i>
                Accessories</button>
            <button class="filter-btn" onclick="filterProducts('souvenirs')"><i class="fa fa-gift"></i>
                Souvenirs</button>
        </div>
        <div class="product-grid" id="productGrid">
            <!-- javascript work here...-->

        </div>

    </section>
    <!-- cart -->
    <div class="cart-modal" id="cart-modal">
        <div class="cart-content">
            <div>
                <h3><i class="fa fa-shopping-cart"></i> Your Cart</h3>
                <button class="close-cart-btn"><i class="fa fa-times" onclick="toggleCart()"></i></button>
                <!-- close cart button -->

                <div id="cartItems"> <!-- cart items will be added here... -->
                    <div class="empty-cart">
                        <div class="empty-cart-logo"><i class="fa fa-shopping-cart"></i></div>
                        <p>Your cart is empty</p>
                    </div>
                </div>
                <div class="cart-total" id="cartTotal" style="display: none;">
                    <div class="total-price" id="totalPrice">Total: $0.00</div>
                    <button class="checkout-btn" onclick="showCheckout()">Checkout</button>
                </div>
                <div class="checkout-form" id="checkoutForm"> <!-- checkout form -->
                    <h3><i class="fa fa-lock"></i> Checkout</h3>
                    <form onsubmit="processOrder(event)" id="checkoutFormElement"> <!-- form submission -->
                        <div class="form-group">
                            <h4><i class="fa fa-box"></i> Shipping Information</h4>
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required>

                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="tel" id="mobile" name="mobile" required>
                        </div>
                        <div class="form-group">
                            <label for="address2">Address 2</label>
                            <input type="text" id="address2" name="address2">
                        </div>

                        <div class="form-row">

                            <div class="form-group">
                                <label for="zip">ZIP/Postal Code</label>
                                <input type="text" id="zip" name="zip" required>
                            </div>
                            <div class="form-group">

                                <label for="city">City</label>
                                <input type="text" id="city" name="city" required>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="state">Province</label>
                            <input type="text" id="state" name="state" required>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <select name="country" id="country" required>
                                <option value="">Select Country</option>
                                <option value="KSA">Saudi Arabia</option>
                                <option value="US">United States</option>
                                <option value="CA">Canada</option>
                                <option value="UK">United Kingdom</option>
                                <option value="AU">Australia</option>
                                <!-- too lazy to add more countries... :P -->
                            </select>
                        </div>
                        <div class="form-section"> <!-- pay info -->
                            <h4><i class="fa fa-credit-card"></i> Payment Information</h4>
                            <div class="payment-methods">
                                <div class="payment-method active" onclick="selectPaymentMethod('creditcard')"><i
                                        class="fa fa-credit-card"></i> Credit Card</div>
                                <div class="payment-method" onclick="selectPaymentMethod('paypal')"><i
                                        class="fab fa-paypal"></i>PayPal</div>
                                <div class="payment-method" onclick="selectPaymentMethod('applepay')"><i
                                        class="fab fa-apple"></i>Apple Pay</div>
                            </div>
                            <div class="credicardForm" id="creditCardInfo">
                                <div class="form-group">
                                    <label for="cardNumber">Card Number</label>
                                    <input type="text" id="cardNumber" name="cardNumber"
                                           placeholder="1234 5678 9012 3456" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="expiryDate">Expiry Date</label>
                                        <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cvv">CVV</label>
                                        <input type="text" id="cvv" name="cvv" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="checkout-total" id="checkoutTotal"> <!-- checkout total price -->
                            <div class="totalPrice" id="checkoutTotalPrice">Total: $0.00</div>
                            <button type="submit" class="place-order-btn"><i class="fas fa-rocket"></i> Place
                                Order</button>
                        </div>
                        <div class="back-to-cart-btn">
                            <button class="back" onclick="toggleCheckout()"><i class="fa fa-arrow-left"></i> Back to
                                Cart</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
