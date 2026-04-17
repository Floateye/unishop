    <header class="header"> <!-- header content -->
        <nav class="nav">
            <div class="logo">IAU UniShop</div>
            <ul class="nav-links"><!-- navigation links -->
                <li><a href="{{ route('welcome') }}">Home</a></li>
                <li><a href="{{ route('products.index') }}">Shop</a></li>
                <li><a href="{{ route('products.index') }}#aboutus">About us!</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
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
