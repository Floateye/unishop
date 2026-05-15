    @php $onStorePage = request()->routeIs('products.index'); @endphp
    <header class="header"> <!-- header content -->
        <nav class="nav">
            <div class="logo">IAU UniShop</div>
            <ul class="nav-links"><!-- navigation links -->
                <li><a href="{{ $onStorePage ? '#home' : route('products.index') . '#home' }}">Home</a></li>
                <li><a href="{{ $onStorePage ? '#products' : route('products.index') . '#products' }}">Shop</a></li>
                <li><a href="{{ $onStorePage ? '#aboutus' : route('products.index') . '#aboutus' }}">About us!</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                @guest
                <li><a href="{{ route('welcome') }}">Login</a></li>
                @endguest
            </ul>
            <div class="nav-action">
                @auth
                <!-- Profile button -->
                <div class="profile-wrapper" id="profileWrapper">
                    <button class="profile-btn" id="profileBtn" onclick="toggleProfilePopup()" aria-label="Profile">
                        <span class="profile-avatar" id="profileAvatar">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile" id="profileAvatarImg">
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </span>
                        <span class="profile-name-short">{{ auth()->user()->first_name }}</span>
                    </button>

                    <!-- Profile popup -->
                    <div class="profile-popup" id="profilePopup">
                        <div class="profile-popup-header">
                            <div class="profile-popup-avatar">
                                @if(auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile" id="profilePopupImg">
                                @else
                                    <div class="profile-popup-icon" id="profilePopupIcon"><i class="fas fa-user"></i></div>
                                @endif
                                <label class="profile-pic-upload-label" for="profilePicInput" title="Change photo">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="profilePicInput" accept="image/*" style="display:none" onchange="uploadProfilePicture(this)">
                            </div>
                            <div class="profile-popup-info">
                                <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</strong>
                                <span>{{ auth()->user()->email }}</span>
                                @if(auth()->user()->addresses->first())
                                    <span class="profile-address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ auth()->user()->addresses->first()->address }}, {{ auth()->user()->addresses->first()->city }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @php $orders = auth()->user()->orders()->latest()->take(3)->get(); @endphp
                        @if($orders->count() > 0)
                        <div class="profile-popup-section">
                            <h4>Recent Orders</h4>
                            @foreach($orders as $order)
                            <div class="profile-order-item">
                                <span>Order #{{ $order->id }}</span>
                                <span class="profile-order-total">{{ number_format($order->total_amount, 2) }} SAR</span>
                                <span class="profile-order-status {{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="profile-popup-section profile-no-orders">
                            <i class="fas fa-box-open"></i>
                            <span>No orders yet</span>
                        </div>
                        @endif

                        <form action="{{route('logout')}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="profile-popup-footer">
                                <button type="submit" class="profile-logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endauth

                @auth
                @if(auth()->user()->hasRole('Admin'))
                <a href="{{ route('dashboard') }}" class="cart-btn" style="text-decoration:none;">
                    <i class="fas fa-shield-alt"></i> Admin Panel
                </a>
                @endif
                @endauth
                <button class="cart-btn" onclick="toggleCart()">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="cart-count" id="cartCount">0</span>
                    Cart
                </button>
            </div>
        </nav>
    </header>
