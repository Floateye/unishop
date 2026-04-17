<x-guest-layout>
    <x-store-navbar />

    <section class="contact-section"> <!-- main contact markup layout :D -->
        <div class="section-title">
            <h2>Contact Us</h2>
            <p>We'd love to hear from you!</p>
        </div>

        <div class="contact-container"><!-- main stuff to be used -->
            <div class="contact-details">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i> <!-- fontawesome is amazing -->
                    <span>123 University Blvd, Dammam, Saudi Arabia</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>+966 13 123 4567</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>support@iau-unishop.edu.sa</span>
                </div>
                <div class="contact-item"> 
                    <i class="fas fa-clock"></i>
                    <span>Sun-Thu: 8:00 AM - 3:00 PM</span>
                </div>
            </div>
            <div class="contact-actions">
                <a href="{{ route('products.index') }}" class="shop-btn">Return to Store</a>
            </div>
        </div>
    </section>

    <x-store-cart />
</x-guest-layout>
