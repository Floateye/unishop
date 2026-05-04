<x-guest-layout>
    <x-store-navbar />

    <section class="contact-section"> <!-- main contact markup layout :D -->
        <div class="contact-bg"></div>
        <div class="section-title">
            <h2>Contact Us</h2>
            <p>We'd love to hear from you!</p>
        </div>

        <div class="contact-grid">
            <div class="contact-info">
                <div class="contact-details">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
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
                    <a href="{{ route('products.index') }}" class="contact-btn">Return to Store</a>
                </div>
            </div>

            <div class="contact-map">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3576.241595150654!2d50.188734!3d26.318451!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e49e612301135ef%3A0xc4328f6458567117!2sImam%20Abdulrahman%20Bin%20Faisal%20University!5e0!3m2!1sen!2ssa!4v1714821800000!5m2!1sen!2ssa" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <x-store-cart />
</x-guest-layout>
