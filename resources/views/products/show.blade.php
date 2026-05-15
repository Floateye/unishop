<x-admin-layout>
    <x-status/>
    <div class="admin-container">

        <section class="admin-section">
            <div class="admin-card-header" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                <a href="{{ route('dashboard') }}#manage" class="admin-btn-edit" style="text-decoration:none;">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <div>
                    <h2><i class="fas fa-box-open"></i> {{ $product->name }}</h2>
                    <p>Product detail and review sentiment analysis.</p>
                </div>
            </div>
            <div class="divider"></div>

            <div style="display:flex;gap:1.5rem;flex-wrap:wrap;align-items:flex-start;">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         style="width:160px;height:160px;object-fit:cover;border-radius:10px;border:1px solid #dde4f5;">
                @endif
                <div style="flex:1;min-width:200px;">
                    <p><strong>Category:</strong> {{ $product->category->name }}</p>
                    <p><strong>Price:</strong> {{ $product->price }} SAR</p>
                    <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                    @if($product->description)
                        <p><strong>Description:</strong> {{ $product->description }}</p>
                    @endif
                </div>
            </div>
        </section>

        <!-- Sentiment Overview -->
        <section class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-chart-bar"></i> Sentiment Overview</h2>
                <p>Breakdown of AI-determined sentiment across all {{ $reviews->count() }} review{{ $reviews->count() !== 1 ? 's' : '' }}.</p>
            </div>
            <div class="divider"></div>

            @php
                $total     = $reviews->count();
                $avgRating = $total > 0 ? round($reviews->avg('rating'), 1) : null;
                $bars = [
                    'positive' => ['label' => 'Positive', 'color' => '#28a745', 'count' => $sentimentCounts['positive']],
                    'negative' => ['label' => 'Negative', 'color' => '#e3342f', 'count' => $sentimentCounts['negative']],
                    'mixed'    => ['label' => 'Mixed',    'color' => '#f59e0b', 'count' => $sentimentCounts['mixed']],
                    'neutral'  => ['label' => 'Neutral',  'color' => '#6b7280', 'count' => $sentimentCounts['neutral']],
                    'unknown'  => ['label' => 'Unanalyzed','color' => '#aac1ff','count' => $sentimentCounts['unknown']],
                ];
            @endphp

            @if($total === 0)
                <div class="empty-table"><p>No reviews yet.</p></div>
            @else
                <div style="display:flex;gap:0.5rem;margin-bottom:0.75rem;">
                    <span class="admin-badge badge-primary">{{ $total }} review{{ $total !== 1 ? 's' : '' }}</span>
                    @if($avgRating)
                        <span class="admin-badge" style="background:#102f85;color:#fff;">
                            &#9733; {{ $avgRating }} / 5 avg
                        </span>
                    @endif
                </div>

                <div style="display:flex;flex-direction:column;gap:0.65rem;max-width:500px;">
                    @foreach($bars as $bar)
                        @if($bar['count'] > 0)
                            @php $pct = round(($bar['count'] / $total) * 100); @endphp
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <span style="width:80px;font-size:0.85rem;font-weight:600;color:#333;">{{ $bar['label'] }}</span>
                                <div style="flex:1;background:#e8edf8;border-radius:999px;height:18px;overflow:hidden;">
                                    <div style="width:{{ $pct }}%;background:{{ $bar['color'] }};height:100%;border-radius:999px;transition:width 0.4s;"></div>
                                </div>
                                <span style="font-size:0.82rem;color:#555;white-space:nowrap;">{{ $bar['count'] }} ({{ $pct }}%)</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Gemma AI Summary -->
        <section class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-robot"></i> AI Review Summary <span style="font-size:0.75rem;font-weight:400;color:#888;">(Gemma)</span></h2>
                <p>Ask Gemma to condense all reviews into key positives and negatives.</p>
            </div>
            <div class="divider"></div>

            @if($reviews->isEmpty())
                <div class="empty-table"><p>No reviews to summarize.</p></div>
            @else
                <button id="generateSummaryBtn" class="auth-submit" style="max-width:220px;"
                        onclick="generateSummary({{ $product->id }})">
                    <i class="fas fa-magic"></i> Generate Summary
                </button>
                <div id="summaryResult" style="margin-top:1rem;display:none;">
                    <div id="summaryText" style="background:#f0f4ff;border-left:4px solid #102f85;padding:1rem 1.25rem;border-radius:6px;font-size:0.95rem;line-height:1.6;color:#333;white-space:pre-wrap;"></div>
                </div>
            @endif
        </section>

        <!-- Reviews Table -->
        <section class="admin-section" id="reviews">
            <div class="admin-card-header">
                <h2><i class="fas fa-star"></i> Reviews</h2>
                <p>All reviews for this product. Admins can delete any review.</p>
            </div>
            <div class="divider"></div>
            <div class="admin-table-wrapper">
                @if($reviews->isNotEmpty())
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Sentiment</th>
                            <th>Review</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="reviewsTableBody">
                        @foreach($reviews as $review)
                            <tr id="review-row-{{ $review->id }}">
                                <td>{{ $review->user->first_name }} {{ $review->user->last_name }}</td>
                                <td><span class="admin-badge badge-primary">{{ $review->rating }} / 5</span></td>
                                <td>
                                    @if($review->sentiment === 'positive')
                                        <span class="admin-badge badge-success">Positive</span>
                                    @elseif($review->sentiment === 'negative')
                                        <span class="admin-badge badge-warning">Negative</span>
                                    @elseif($review->sentiment === 'mixed')
                                        <span class="admin-badge" style="background:#f59e0b;color:#fff;">Mixed</span>
                                    @elseif($review->sentiment === 'neutral')
                                        <span class="admin-badge" style="background:#6b7280;color:#fff;">Neutral</span>
                                    @else
                                        <span class="admin-badge" style="background:#aac1ff;color:#333;">N/A</span>
                                    @endif
                                </td>
                                <td style="max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                    title="{{ $review->body }}">{{ $review->body }}</td>
                                <td>{{ $review->created_at->format('M j, Y') }}</td>
                                <td class="admin-tbl-actions">
                                    <button type="button"
                                            onclick="confirmDeleteReview({{ $review->id }})"
                                            class="admin-btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-table"><p>No reviews for this product.</p></div>
                @endif
            </div>
        </section>

    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        function generateSummary(productId) {
            const btn = document.getElementById('generateSummaryBtn');
            const resultEl = document.getElementById('summaryResult');
            const textEl = document.getElementById('summaryText');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
            resultEl.style.display = 'none';

            fetch(`/products/${productId}/review-summary`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    textEl.style.borderLeftColor = '#e3342f';
                    textEl.textContent = 'Error: ' + data.error;
                } else {
                    textEl.style.borderLeftColor = '#102f85';
                    textEl.textContent = data.summary;
                }
                resultEl.style.display = 'block';
            })
            .catch(() => {
                textEl.style.borderLeftColor = '#e3342f';
                textEl.textContent = 'Failed to connect to the summary service.';
                resultEl.style.display = 'block';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-magic"></i> Generate Summary';
            });
        }

        function confirmDeleteReview(id) {
            Swal.fire({
                title: 'Delete this review?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(`/reviews/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(() => {
                    const row = document.getElementById('review-row-' + id);
                    if (row) row.remove();

                    const tbody = document.getElementById('reviewsTableBody');
                    if (tbody && tbody.querySelectorAll('tr').length === 0) {
                        tbody.closest('.admin-table-wrapper').innerHTML =
                            '<div class="empty-table"><p>No reviews for this product.</p></div>';
                    }
                })
                .catch(() => Swal.fire('Error', 'Could not delete the review.', 'error'));
            });
        }
    </script>
</x-admin-layout>
