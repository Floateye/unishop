<x-admin-layout>
    <div class="admin-container">

        {{-- Order Header --}}
        <section class="admin-section">
            <div class="order-detail-header">
                <div>
                    <h2 class="order-detail-title">
                        <i class="fas fa-receipt"></i>
                        Order #{{ $order->id }}
                    </h2>
                    <p class="order-detail-meta">
                        Placed on {{ $order->created_at->format('M j, Y \a\t g:i A') }}
                        &nbsp;&bull;&nbsp;
                        Customer: <strong>{{ $order->user->name }}</strong>
                    </p>
                </div>
                <div class="order-detail-status">
                    <span class="admin-badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                        <i class="fas {{ $order->payment_status === 'paid' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        {{ ucfirst($order->payment_status) }}
                    </span>
                   <div>
                       <a href="/dashboard#orders" class="admin-btn-view" style="margin-left:1rem;">
                           <i class="fas fa-arrow-left"></i> Back to Orders
                       </a>
                       <a href="{{ route('orders.invoice', $order->id) }}" class="admin-btn-view" style="margin-left:1rem;">
                           <i class="fas fa-file-pdf"></i> Download Invoice
                       </a>
                   </div>
                </div>
            </div>
        </section>

        {{-- Order Items --}}
        <section class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-boxes"></i> Order Items</h2>
                <p>{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }} in this order.</p>
            </div>
            <div class="divider"></div>

            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="admin-tbl-img">
                                    @else
                                        <div class="admin-tbl-img-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <div class="order-item-name">{{ $item->product->name }}</div>
                                    @if($item->product->category)
                                        <div class="order-item-cat">{{ $item->product->category->name }}</div>
                                    @endif
                                </td>
                                <td>{{ number_format($item->unit_price, 2) }} SAR</td>
                                <td>
                                    <span class="admin-badge badge-primary">{{ $item->quantity }}</span>
                                </td>
                                <td><strong>{{ number_format($item->unit_price * $item->quantity, 2) }} SAR</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="order-total-row">
                            <td colspan="4" style="text-align:right; font-weight:600; color:#031034;">
                                Total Amount
                            </td>
                            <td>
                                <span class="order-total-amount">{{ number_format($order->total_amount, 2) }} SAR</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        {{-- Shipping Snapshot --}}
        <section class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-map-marker-alt"></i> Shipping Details</h2>
                <p>Snapshot captured at the time the order was placed.</p>
            </div>
            <div class="divider"></div>
            <div class="order-snapshot-grid">
                @foreach(json_decode($order->shipping_snapshot, true) as $key => $value)
                    <div class="order-snapshot-item">
                        <span class="order-snapshot-label">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        <span class="order-snapshot-value">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Payment Method (placeholder) --}}
        <section class="admin-section order-payment-placeholder">
            <div class="admin-card-header">
                <h2><i class="fas fa-credit-card"></i> Payment Method</h2>
                <p>Payment method details will be available in a future update.</p>
            </div>
            <div class="divider"></div>
            <div class="order-payment-coming-soon">
                <i class="fas fa-lock"></i>
                <p>Payment gateway integration coming soon.</p>
            </div>
        </section>

    </div>
</x-admin-layout>
