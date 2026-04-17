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
    <!-- cart blade insertion here :D -->
    <x-store-cart />

</x-guest-layout>
