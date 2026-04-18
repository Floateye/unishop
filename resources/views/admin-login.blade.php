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

    <main class="auth-page-admin">
        <section class="auth-shell">
            <div class="auth-showcase"><!-- main page -->
                <span class="auth-eyebrow">Admin Portal</span>
                <h1>Sign in to continue shopping with your IAU staff account.</h1>
                <p>Staff only!!!</p>
                <div class="auth-highlights">
                    <div class="auth-highlight">
                        <i class="fas fa-cog"></i>
                        <span>Sleek admin login.</span>
                    </div>
                    <div class="auth-highlight">
                        <i class="fas fa-shield-alt"></i>
                        <span>Control all product order and other related stuff.</span>
                    </div>
                    <div class="auth-highlight">
                        <i class="fas fa-cogs"></i>
                        <span>A neat different color, although we really just reused our own first login.</span>
                    </div>
                </div>
            </div>

            <div class="auth-card"><!-- the login itself -->
                <div class="auth-card-header">
                    <span class="auth-icon-red"><i class="fas fa-user-circle"></i></span>
                    <h2>Admin Login</h2>
                    <p>Enter your account details to continue.</p>
                </div>

                <form class="auth-form" action="{{route('admin-login.store')}}" method="post">
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

                    <button type="submit" class="auth-btn-admin">Login</button>
                </form>

                <div class="auth-links">
                    <a href="{{ route('welcome') }}" class="back">Back</a>
                    <a href="{{ route('register.create') }}" class="auth-text-link">Create an account</a>
                    <a class="auth-text-link" href="{{ route('login.create') }}">User Login</a>
                </div>

                
            </div>
        </section>
    </main>

    <script>
        function handleLogin(e) {
            e.preventDefault();
            window.location.href = '{{ route('admin.index') }}';
        }
    </script>
</x-guest-layout>
