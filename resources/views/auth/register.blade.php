<x-guest-layout>
    <header class="welcome-header">
        <nav class="nav">
            <div class="logo">IAU UniShop</div>
            <ul class="nav-links">
                <li><a href="{{ route('welcome') }}">Welcome</a></li>
                <li><a href="{{ route('login.create') }}">Login</a></li>
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
                <span class="auth-eyebrow">New Account</span>
                <h1>Create your UniShop account and save your shopping details.</h1>
                <p>Register once and continue with a cleaner customer flow for future orders, campus merchandise browsing, and account-based shopping.</p>
                <div class="auth-highlights">
                    <div class="auth-highlight">
                        <i class="fas fa-user-plus"></i>
                        <span>Create a dedicated customer account</span>
                    </div>
                    <div class="auth-highlight">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Save your contact and shipping details</span>
                    </div>
                    <div class="auth-highlight">
                        <i class="fas fa-shirt"></i>
                        <span>Continue directly into the UniShop storefront</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <div class="auth-card-header">
                    <span class="auth-icon"><i class="fas fa-user-plus"></i></span>
                    <h2>Create Account</h2>
                    <p>Fill in your information to register as a UniShop customer.</p>
                </div>

                <form class="auth-form auth-form-register" action="{{route('register.store')}}" method="post">
                    @csrf
                    @if ($errors->any())
                        <div class="auth-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="auth-field">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                    </div>

                    <div class="auth-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="student@iau.edu.sa" required>
                    </div>

                    <div class="auth-field auth-field-split">
                        <div>
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Create a password" required>
                        </div>
                        <div>
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat the password" required>
                        </div>
                    </div>

                    <div class="auth-field auth-field-split">
                        <div>
                            <label for="phone">Mobile Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="05xxxxxxxx" required>
                        </div>
                        <div>
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="Dammam" required>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Street, building, district" required>
                    </div>

                    <button type="submit" class="auth-submit">Create Account</button>
                </form>

                <div class="auth-links">
                    <a href="{{ route('login.create') }}" class="back">Back to Login</a>
                    <a href="{{ route('products.index') }}" class="auth-text-link">Continue as guest</a>
                </div>
            </div>
        </section>
    </main>

    <script>
        function handleRegister(e) {
            e.preventDefault();
            window.location.href = '{{ route('products.index') }}';
        }
    </script>
</x-guest-layout>
