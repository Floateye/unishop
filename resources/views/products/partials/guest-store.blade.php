<x-guest-layout>
    <x-store-navbar /> <!-- cart blade component here -->
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

    <!-- product modal -->
    <div class="product-modal" id="product-modal">
        <div class="product-modal-content">
            <button class="close-modal-btn" onclick="closeProductModal()"><i class="fa fa-times"></i></button>
            <div class="product-modal-body">
                <img id="modal-product-image" src="" alt="Product Image">
                <div class="product-modal-details">
                    <h2 id="modal-product-title"></h2>
                    <p class="product-category" id="modal-product-category"></p>
                    <div class="product-price" id="modal-product-price"></div>
                    <p id="modal-product-description" class="product-description" style="margin-bottom: 1.5rem; line-height: 1.6;"></p>

                    <div class="size-selection" id="modal-size-container" style="display: none;">
                        <h4>Select Size</h4>
                        <div class="product-sizes" id="modal-size-options">
                            <!-- size buttons injected here -->
                        </div>
                    </div>

                    <button class="add-to-cart" id="modal-add-to-cart" onclick="">Add to Cart</button>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="reviews-section" id="modal-reviews-section">
                <div class="reviews-header">
                    <h3>Reviews</h3>
                    <div class="reviews-avg" id="modal-reviews-avg"></div>
                </div>
                <div class="reviews-list" id="modal-reviews-list">
                    <div class="reviews-loading">Loading reviews...</div>
                </div>
                <!-- Guest: prompt to log in to leave a review -->
                <div class="review-auth-prompt">
                    <a href="{{ route('login.create') }}">Log in</a> to leave a review.
                </div>
            </div>
        </div>
    </div>

    <!-- About Us section -->
    <section class="about-section" id="aboutus">
        <div class="section-title">
            <h2>About UniShop</h2>
            <p>The official merchandise store of Imam Abdulrahman Bin Faisal University.</p>
        </div>
        <div class="about-body">
            <p>UniShop is the home of official IAU-branded merchandise — from clothing to accessories and souvenirs. Every item is university-approved and designed to represent the IAU community with pride.</p>
        </div>
    </section>

    <!-- cart blade insertion here :D -->
    <x-store-cart />

    <!-- Inject real products from DB as JSON -->
    @php
        $mappedProducts = $products->map(function($p) {
            return [
                'id'          => $p->id,
                'title'       => $p->name,
                'category'    => $p->category ? strtolower($p->category->name) : 'other',
                'price'       => (float) $p->price,
                'image'       => $p->image ? asset('storage/' . $p->image) : '/assets/img/tshirt.webp',
                'description' => $p->description,
                'size'        => $p->size ?? [],
            ];
        });
    @endphp
    <script>
        const products = @json($mappedProducts);
        const isAuthenticated = false;
        const csrfToken = '{{ csrf_token() }}';
    </script>

</x-guest-layout>
