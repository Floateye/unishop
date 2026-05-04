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
        </div>
    </div>

    <!-- cart blade insertion here :D -->
    <x-store-cart />

</x-guest-layout>
