<x-guest-layout>
    <header class="welcome-header">
        <nav class="nav">
            <div class="logo">IAU UniShop</div>
            <ul class="nav-links">
                <li><a href="{{ route('welcome') }}">Welcome</a></li>
                <li><a href="{{ route('products.index') }}">Store</a></li>
            </ul>
            <div class="nav-action">
                <a href="{{ route('products.index') }}" class="cart-btn">
                    <i class="fas fa-store"></i>
                    Browse Store
                </a>
            </div>
        </nav>
    </header>

    <main class="auth-page">
        <section class="auth-shell">
            <div class="auth-showcase">
                <span class="auth-eyebrow">User Portal</span>
                <h1>Sign in to continue shopping with your IAU account.</h1>
                <p>Access your shopping flow with a page that matches the same storefront style, colors, and visual tone as UniShop.</p>
                <div class="auth-highlights">
                    <div class="auth-highlight">
                        <i class="fas fa-bag-shopping"></i>
                        <span>Continue to the store after login</span>
                    </div>
                    <div class="auth-highlight">
                        <i class="fas fa-shield-alt"></i>
                        <span>Separate customer and admin entry points</span>
                    </div>
                    <div class="auth-highlight">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Campus-branded shopping experience</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <div class="auth-card-header">
                    <span class="auth-icon"><i class="fas fa-user-circle"></i></span>
                    <h2>User Login</h2>
                    <p>Enter your account details to continue.</p>
                </div>

                <form class="auth-form" action="{{route('login.store')}}" method="post">
                    @csrf
                    @if ($errors->any())
                        <div class="auth-error">
                            {{ $errors->first('email') ?? $errors->first('password') }}
                        </div>
                    @endif

                    <div class="auth-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="student@iau.edu.sa" required>
                    </div>

                    <div class="auth-field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="auth-submit">Login</button>
                </form>

                <div class="auth-links">
                    <a href="{{ route('welcome') }}" class="back">Back</a>
                    <a href="{{ route('register.create') }}" class="auth-text-link">Create an account</a>
                    <a class="auth-text-link" href="{{ route('admin-login') }}">Admin Login</a>
                </div>

                
            </div>
        </section>
    </main>

    <script>
        function handleLogin(e) {
            e.preventDefault();
            window.location.href = '{{ route('products.index') }}';
        }
    </script>
</x-guest-layout>
