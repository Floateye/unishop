<x-guest-layout>
    <header class="welcome-header">
        <nav class="nav">
            <div class="logo">IAU UniShop</div>
            <ul class="nav-links">
                <li><a href="#welcome">Welcome</a></li>
                <li><a href="#preview">Why Shop Here</a></li>
                <li><a href="{{ route('store') }}">Store</a></li>
            </ul>
            <div class="nav-action">
                <a href="{{ route('store') }}" class="cart-btn">
                    <i class="fas fa-store"></i>
                    Visit Store
                </a>
            </div>
        </nav>
    </header>

    <section class="hero" id="welcome">
        <div class="hero-bg"></div>
        <div class="welcome-panel">
            <div class="hero-content">
                <h1>Welcome to IAU UniShop</h1>
                <p>The official Imam Abdulrahman bin Faisal University merchandise store with campus-inspired clothing, accessories, and souvenirs.</p>
                <p>Choose how you want to enter the site and continue with the same look and feel as the rest of the storefront.</p>
                <div class="welcome-actions">
                    <a href="{{ route('login') }}" class="shop-btn">User Login</a>
                    <a href="{{ route('admin.login') }}" class="shop-btn welcome-secondary">Admin Login</a>
                    <a href="{{ route('store') }}" class="shop-btn">Continue as Guest</a>
                </div>
                <div class="welcome-features">
                    <div class="welcome-feature">
                        <strong>Campus Gear</strong>
                        <span>Official university-themed products.</span>
                    </div>
                    <div class="welcome-feature">
                        <strong>Easy Checkout</strong>
                        <span>Simple browsing and ordering flow.</span>
                    </div>
                    <div class="welcome-feature">
                        <strong>Admin Access</strong>
                        <span>Dedicated portal for store management.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="welcome-preview" id="preview">
        <div class="section-title">
            <h2>Why UniShop</h2>
            <p>Built to match the visual style of the main storefront.</p>
        </div>
        <div class="welcome-preview-grid">
            <article class="welcome-card">
                <i class="fas fa-tshirt"></i>
                <h3>Branded Collections</h3>
                <p>Explore clothing, accessories, and souvenirs designed around the university identity.</p>
            </article>
            <article class="welcome-card">
                <i class="fas fa-shipping-fast"></i>
                <h3>Smooth Shopping Flow</h3>
                <p>From browsing to checkout, the pages share one consistent interface and interaction style.</p>
            </article>
            <article class="welcome-card">
                <i class="fas fa-user-shield"></i>
                <h3>Separate Admin Entry</h3>
                <p>Admins can jump straight to management while customers continue into the store experience.</p>
            </article>
        </div>
    </section>
</x-guest-layout>
