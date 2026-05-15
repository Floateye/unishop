<x-guest-layout>
    <x-store-navbar />
    
    <div class="store-container" style="padding-top: 100px; min-height: 80vh; max-width: 800px; margin: 0 auto;">
        <div class="admin-form-card" style="padding: 2rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <i class="fas fa-check-circle" style="font-size: 3rem; color: #108579; margin-bottom: 1rem;"></i>
                <h1 style="color: #031034; font-size: 2rem; font-weight: 700;">Order Confirmed!</h1>
                <p style="color: #666; font-size: 1.1rem; margin-top: 0.5rem;">Thank you for your purchase. Your order ID is <strong>#{{ $order->id }}</strong>.</p>
            </div>

            <div class="divider"></div>

            <div style="margin-bottom: 2rem;">
                <h3 style="color: #031034; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Order Details</h3>
                <div style="background: #f8f9fa; border-radius: 8px; padding: 1.5rem;">
                    @foreach($order->items as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #eaeaea; padding-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div style="width: 50px; height: 50px; background: #eaeaea; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                        <i class="fas fa-image" style="color: #999;"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong style="color: #333; display: block;">{{ $item->product->name }}</strong>
                                    <span style="color: #888; font-size: 0.9rem;">Qty: {{ $item->quantity }}</span>
                                </div>
                            </div>
                            <div style="font-weight: 600; color: #333;">
                                {{ number_format($item->unit_price * $item->quantity, 2) }} SAR
                            </div>
                        </div>
                    @endforeach
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; font-size: 1.25rem; font-weight: 700; color: #031034;">
                        <span>Total</span>
                        <span>{{ number_format($order->total_amount, 2) }} SAR</span>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h3 style="color: #031034; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem;">Shipping Details</h3>
                <div style="background: #f8f9fa; border-radius: 8px; padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    @foreach(is_array($order->shipping_snapshot) ? $order->shipping_snapshot : json_decode($order->shipping_snapshot, true) ?? [] as $key => $value)
                        <div>
                            <span style="color: #888; font-size: 0.85rem; text-transform: uppercase; display: block;">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                            <span style="color: #333; font-weight: 500;">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <button onclick="window.print()" class="auth-submit" style="width: auto; margin-right: 1rem; background-color: #6c757d;">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
                <a href="{{ route('products.index') }}" class="auth-submit" style="display: inline-block; width: auto; text-decoration: none;">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
