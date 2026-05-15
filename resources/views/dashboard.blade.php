<x-admin-layout>
    <x-status/>
    <div class="admin-container">

        <!-- Operation Overview -->
        <section id="dashboard" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-chart-pie"></i> Operation Overview</h2>
                <p>High-level metrics for the store.</p>
            </div>
            <div class="admin-stats-grid">
                <div class="admin-stat-card">
                    <i class="fas fa-box-open"></i>
                    <div class="admin-stat-info">
                        <h3>Total Products</h3>
                        <strong>{{$totalProducts}}</strong>
                    </div>
                </div>
                <div class="admin-stat-card" style="background: linear-gradient(135deg, #108579 0%, #03342c 100%);">
                    <i class="fas fa-shopping-bag"></i>
                    <div class="admin-stat-info">
                        <h3>Total Orders</h3>
                        <strong>{{$totalOrders}}</strong>
                    </div>
                </div>
                <div class="admin-stat-card" style="background: linear-gradient(135deg, #851042 0%, #340317 100%);">
                    <i class="fas fa-users"></i>
                    <div class="admin-stat-info">
                        <h3>Registered Users</h3>
                        <strong>{{$allUsers}}</strong>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add New Product -->
        <section id="add-product" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-folder-plus"></i> Add New Product</h2>
                <p>Register a new item into the inventory.</p>
            </div>
            <div class="divider"></div>
            <div class="admin-form-card">
                <form id="add-product-form" method="post" action="{{route('products.store')}}" class="auth-form" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->getBag('default')->any())
                        <div class="auth-error">
                            <ul>
                                @foreach ($errors->getBag('default')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" value="{{old('name')}}" placeholder="e.g. Premium IAU Hoodie" required>
                        </div>
                        <div class="auth-field">
                            <label for="price">Price (SAR)</label>
                            <input type="number" id="price" name="price" value="{{old('price')}}" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="category_id">Category</label>
                        <select class="category-selection" id="category_id" name="category_id" required>
                            <option value="">Select Category...</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="auth-field">
                        <label>Image</label>
                        <div class="image-label">
                            <input type="file" id="image" name="image" required>
                            <button type="button" onclick="clearField()" class="clear-button">
                                <i class="fa fa-times"></i> Clear
                            </button>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="{{old('quantity')}}" placeholder="1" id="quantity">
                    </div>

                    <div class="auth-field">
                        <label>Sizes <span style="font-size:0.8rem;font-weight:400;color:#888;">(leave all unchecked if not applicable)</span></label>
                        <div class="size-checkboxes">
                            @foreach(['XS','S','M','L','XL','XXL'] as $sz)
                                <label class="size-checkbox-label">
                                    <input type="checkbox" name="size[]" value="{{ $sz }}"
                                        {{ is_array(old('size')) && in_array($sz, old('size')) ? 'checked' : '' }}>
                                    {{ $sz }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Enter product details..." required>{{old('description')}}</textarea>
                    </div>

                    <div class="admin-form-actions">
                        <button type="submit" class="auth-submit">
                            <i class="fas fa-save"></i> Save Product to Database
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Inventory Management -->
        <section id="manage" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-warehouse"></i> Inventory Management</h2>
                <p>Search and manage all products in the store.</p>
            </div>
            <div class="divider"></div>

            <form method="GET" action="{{ route('dashboard') }}" class="admin-search-form">
                <input type="text" name="q" value="{{ $searchQuery }}"
                       placeholder="Search by product name or description..." class="admin-search-input">
                <button type="submit" class="auth-submit" style="width:auto;padding:0.6rem 1.5rem;">
                    <i class="fas fa-search"></i> Search
                </button>
                @if($searchQuery)
                    <a href="{{ route('dashboard') }}#manage" class="admin-btn-edit" style="padding:0.6rem 1rem;text-decoration:none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>

            @if($searchQuery !== '')
                <div class="admin-table-wrapper" style="margin-top:1.25rem;">
                    @if($searchResults->isNotEmpty())
                        <table class="admin-table">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($searchResults as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="admin-tbl-img">
                                        @else
                                            <div class="admin-tbl-img-placeholder"><i class="fas fa-image"></i></div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->price }} SAR</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td class="admin-tbl-actions">
                                        <a href="{{ route('products.show', $product) }}" class="admin-btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="admin-btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form id="delete-form-{{ $product->id }}" method="POST"
                                              action="{{ route('products.destroy', $product) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                    class="admin-btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-table">
                            <p>No products found for "{{ $searchQuery }}".</p>
                        </div>
                    @endif
                </div>
                <div class="divider" style="margin-top:1.5rem;"></div>
                <p style="font-size:0.85rem;color:#888;margin-bottom:0.75rem;">Full inventory below:</p>
            @endif

            <div class="admin-table-wrapper">
                @if($products->isNotEmpty())
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Slug</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="admin-tbl-img">
                                    @else
                                        <div class="admin-tbl-img-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->slug }}</td>
                                <td>
                                    <span class="admin-badge badge-primary">{{ $product->quantity }}</span>
                                    ({{ $product->category->name }})
                                </td>
                                <td>{{ $product->price }} SAR</td>
                                <td class="admin-tbl-actions">
                                    <a href="{{ route('products.edit', $product) }}" class="admin-btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('products.show', $product) }}" class="admin-btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <form id="delete-form-{{ $product->id }}" method="POST"
                                          action="{{ route('products.destroy', $product) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                                class="admin-btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-table"><p>There are no products</p></div>
                @endif
                <div class="pagination-wrapper">
                    {{ $products->links('pagination.admin', ['pageLink' => 'manage']) }}
                </div>
            </div>
        </section>

        <!-- Customer Orders -->
        <section id="orders" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-file-invoice-dollar"></i> Customer Orders</h2>
                <p>Search by order ID, customer name, or product name.</p>
            </div>
            <div class="divider"></div>

            <form method="GET" action="{{ route('dashboard') }}" class="admin-search-form">
                <input type="text" name="order_q" value="{{ $orderQ }}"
                       placeholder="Order ID, customer name, or product…" class="admin-search-input">
                <button type="submit" class="auth-submit" style="width:auto;padding:0.6rem 1.5rem;">
                    <i class="fas fa-search"></i> Search
                </button>
                @if($orderQ)
                    <a href="{{ route('dashboard') }}#orders" class="admin-btn-edit" style="padding:0.6rem 1rem;text-decoration:none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>

            <div class="admin-table-wrapper" style="margin-top:1rem;">
                @if($orders->isNotEmpty())
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User Name</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{$order->id}}</td>
                                <td>{{$order->user->first_name ?? 'Guest'}} {{$order->user->last_name ?? ''}}</td>
                                <td>{{$order->total_amount}} SAR</td>
                                <td><span class="admin-badge {{$order->payment_status === 'paid' ? 'badge-success' : 'badge-warning'}}">{{$order->payment_status}}</span></td>
                                <td class="admin-tbl-actions">
                                    <a href="{{ route('orders.show', $order) }}" class="admin-btn-edit">
                                        <i class="fas fa-eye"></i> View Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-table"><p>No orders</p></div>
                @endif
                <div class="pagination-wrapper">
                    {{ $orders->links('pagination.admin', ['pageLink' => 'orders']) }}
                </div>
            </div>
        </section>

        <!-- Discounts -->
        <section id="discounts" class="admin-section">
            <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:0.75rem;">
                <div>
                    <h2><i class="fas fa-tag"></i> Discounts</h2>
                    <p>Create and manage discount codes for customers.</p>
                </div>
                @php $showCreateDiscountForm = $errors->createDiscount->any(); @endphp
                <button class="admin-btn-view" onclick="toggleCreateDiscountForm()" id="createDiscountToggle" style="align-self:center;">
                    @if($showCreateDiscountForm)
                        <i class="fas fa-times"></i> Cancel
                    @else
                        <i class="fas fa-plus"></i> Add Discount
                    @endif
                </button>
            </div>
            <div class="divider"></div>

            <form method="GET" action="{{ route('dashboard') }}" class="admin-search-form">
                <input type="text" name="discount_q" value="{{ $discountQ }}"
                       placeholder="Search by code…" class="admin-search-input">
                <button type="submit" class="auth-submit" style="width:auto;padding:0.6rem 1.5rem;">
                    <i class="fas fa-search"></i> Search
                </button>
                @if($discountQ)
                    <a href="{{ route('dashboard') }}#discounts" class="admin-btn-edit" style="padding:0.6rem 1rem;text-decoration:none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>

            <!-- Create Discount Form (collapsible) -->
            <div id="createDiscountForm" style="{{ $showCreateDiscountForm ? '' : 'display:none;' }}" class="admin-form-card" style="margin-bottom:1.5rem;">
                <h3 style="margin-bottom:1rem;font-size:1rem;color:#555;font-weight:600;"><i class="fas fa-tag"></i> Create New Discount Code</h3>
                @if($errors->createDiscount->any())
                    <div class="auth-error">
                        <ul>
                            @foreach($errors->createDiscount->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('discounts.store') }}" class="auth-form">
                    @csrf
                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="dc_code">Code <span style="font-size:0.8rem;font-weight:400;color:#888;">(max 8 chars)</span></label>
                            <input type="text" id="dc_code" name="code" maxlength="8"
                                   placeholder="e.g. SAVE20" value="{{ old('code') }}"
                                   required style="text-transform:uppercase;letter-spacing:0.1em;">
                        </div>
                        <div class="auth-field">
                            <label for="dc_rate">Discount % <span style="font-size:0.8rem;font-weight:400;color:#888;">(1–100)</span></label>
                            <input type="number" id="dc_rate" name="rate" min="1" max="100" step="1"
                                   placeholder="10" value="{{ old('rate') }}" required>
                        </div>
                    </div>
                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="dc_starts">Starts At</label>
                            <input type="datetime-local" id="dc_starts" name="starts_at"
                                   value="{{ old('starts_at') }}" required>
                        </div>
                        <div class="auth-field">
                            <label for="dc_expires">Expires At</label>
                            <input type="datetime-local" id="dc_expires" name="expires_at"
                                   value="{{ old('expires_at') }}" required>
                        </div>
                    </div>
                    <div class="auth-field">
                        <label class="size-checkbox-label" style="display:inline-flex;align-items:center;gap:0.5rem;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            Active immediately
                        </label>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="auth-submit">
                            <i class="fas fa-save"></i> Create Discount
                        </button>
                    </div>
                </form>
            </div>

            <div class="admin-table-wrapper">
            @if($discounts->isNotEmpty())
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Starts</th>
                        <th>Expires</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($discounts as $discount)
                        <tr>
                            <td><strong>{{ $discount->code }}</strong></td>
                            <td>{{ round($discount->rate * 100) }}% off</td>
                            <td>
                                @if($discount->is_active && $discount->starts_at <= now() && $discount->expires_at >= now())
                                    <span class="admin-badge badge-success">Active</span>
                                @elseif($discount->expires_at < now())
                                    <span class="admin-badge badge-warning">Expired</span>
                                @else
                                    <span class="admin-badge" style="background:#6b7280;color:#fff;">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $discount->starts_at->format('M j, Y') }}</td>
                            <td>{{ $discount->expires_at->format('M j, Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-table"><p>No discount codes yet.</p></div>
            @endif
            <div class="pagination-wrapper">
                {{ $discounts->links('pagination.admin', ['pageLink' => 'discounts']) }}
            </div>
            </div>
        </section>

        <!-- Reviews -->
        <section id="reviews" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-star"></i> Customer Reviews</h2>
                <p>Search by product or review text, and filter by star rating.</p>
            </div>
            <div class="divider"></div>

            <form method="GET" action="{{ route('dashboard') }}" class="admin-search-form">
                <input type="text" name="review_q" value="{{ $reviewQ }}"
                       placeholder="Product name or review text…" class="admin-search-input">
                <select name="review_stars" class="admin-search-select">
                    <option value="">All Stars</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ (string)$reviewStars === (string)$i ? 'selected' : '' }}>
                            {{ $i }} ★
                        </option>
                    @endfor
                </select>
                <button type="submit" class="auth-submit" style="width:auto;padding:0.6rem 1.5rem;">
                    <i class="fas fa-search"></i> Search
                </button>
                @if($reviewQ || $reviewStars)
                    <a href="{{ route('dashboard') }}#reviews" class="admin-btn-edit" style="padding:0.6rem 1rem;text-decoration:none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>

            <div class="admin-table-wrapper" style="margin-top:1rem;">
                @if($reviews->isNotEmpty())
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Rating</th>
                            <th>Sentiment</th>
                            <th>Body</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reviews as $review)
                            <tr id="review-row-{{ $review->id }}">
                                <td>{{$review->id}}</td>
                                <td>{{$review->user->first_name}} {{$review->user->last_name}}</td>
                                <td>{{$review->product->name}}</td>
                                <td><span class="admin-badge badge-primary">{{$review->rating}} / 5</span></td>
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
                                        <span class="admin-badge" style="background:#aac1ff;color:#333;">{{$review->sentiment ?? 'N/A'}}</span>
                                    @endif
                                </td>
                                <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{$review->body}}">
                                    {{$review->body}}
                                </td>
                                <td>{{$review->created_at->format('M j, Y')}}</td>
                                <td class="admin-tbl-actions">
                                    <button type="button" onclick="confirmDeleteReview({{ $review->id }})" class="admin-btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-table"><p>No Reviews</p></div>
                @endif
                <div class="pagination-wrapper">
                    {{ $reviews->links('pagination.admin', ['pageLink' => 'reviews']) }}
                </div>
            </div>
        </section>

        <!-- Users & Admins -->
        <section id="users" class="admin-section">
            <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:0.75rem;">
                <div>
                    <h2><i class="fas fa-users-cog"></i> Users & Admins</h2>
                    <p>View all registered users.{{ $isSupervisor ? ' As supervisor, you can promote, demote, add, and delete.' : '' }}</p>
                </div>
                @if($isSupervisor)
                @php $showCreateUserForm = $errors->createUser->any(); @endphp
                <button class="admin-btn-view" onclick="toggleCreateUserForm()" id="createUserToggle" style="align-self:center;">
                    @if($showCreateUserForm)
                        <i class="fas fa-times"></i> Cancel
                    @else
                        <i class="fas fa-user-plus"></i> Add User
                    @endif
                </button>
                @endif
            </div>
            <div class="divider"></div>

            <form method="GET" action="{{ route('dashboard') }}" class="admin-search-form">
                <input type="text" name="user_q" value="{{ $userQ }}"
                       placeholder="Name or email…" class="admin-search-input">
                <select name="user_role" class="admin-search-select">
                    <option value="">All Roles</option>
                    <option value="Admin" {{ $userRole === 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Customer" {{ $userRole === 'Customer' ? 'selected' : '' }}>Customer</option>
                </select>
                <input type="date" name="user_date" value="{{ $userDate }}" class="admin-search-select"
                       title="Filter by date joined">
                <button type="submit" class="auth-submit" style="width:auto;padding:0.6rem 1.5rem;">
                    <i class="fas fa-search"></i> Search
                </button>
                @if($userQ || $userRole || $userDate)
                    <a href="{{ route('dashboard') }}#users" class="admin-btn-edit" style="padding:0.6rem 1rem;text-decoration:none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>

            @if($isSupervisor)
            <!-- Create User Form (collapsible) -->
            <div id="createUserForm" style="{{ $showCreateUserForm ? '' : 'display:none;' }}" class="admin-form-card" style="margin-bottom:1.5rem;">
                <h3 style="margin-bottom:1rem;font-size:1rem;color:#555;font-weight:600;"><i class="fas fa-user-plus"></i> Create New User</h3>
                @if($errors->createUser->any())
                    <div class="auth-error">
                        <ul>
                            @foreach($errors->createUser->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('users.store') }}" class="auth-form">
                    @csrf
                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="cu_first_name">First Name</label>
                            <input type="text" id="cu_first_name" name="first_name"
                                   value="{{ old('first_name') }}" placeholder="First name" required>
                        </div>
                        <div class="auth-field">
                            <label for="cu_last_name">Last Name</label>
                            <input type="text" id="cu_last_name" name="last_name"
                                   value="{{ old('last_name') }}" placeholder="Last name" required>
                        </div>
                    </div>
                    <div class="auth-field">
                        <label for="cu_email">Email</label>
                        <input type="email" id="cu_email" name="email"
                               value="{{ old('email') }}" placeholder="user@example.com" required>
                    </div>
                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="cu_password">Password</label>
                            <input type="password" id="cu_password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="auth-field">
                            <label for="cu_role">Role</label>
                            <select id="cu_role" name="role" class="category-selection" required>
                                <option value="Customer" {{ old('role') === 'Customer' ? 'selected' : '' }}>Customer</option>
                                <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="auth-submit">
                            <i class="fas fa-user-plus"></i> Create User
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <div class="admin-table-wrapper">
                @if($users->isNotEmpty())
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->hasRole('Admin'))
                                        <span class="admin-badge badge-success">Admin</span>
                                        @if($user->admin?->is_supervisor)
                                            <span class="admin-badge" style="background:#7c3aed;color:#fff;margin-left:4px;">Supervisor</span>
                                        @endif
                                    @else
                                        <span class="admin-badge badge-primary">Customer</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="admin-tbl-actions">
                                    @if($user->id !== auth()->id())
                                        @if($isSupervisor)
                                            @if($user->hasRole('Admin'))
                                                <form method="POST" action="{{ route('users.demote', $user) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="admin-btn-edit"
                                                            onclick="return confirm('Demote {{ $user->first_name }} to Customer?')">
                                                        <i class="fas fa-arrow-down"></i> Demote
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('users.promote', $user) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="admin-btn-view"
                                                            onclick="return confirm('Promote {{ $user->first_name }} to Admin?')">
                                                        <i class="fas fa-arrow-up"></i> Promote
                                                    </button>
                                                </form>
                                            @endif
                                            <form id="delete-user-form-{{ $user->id }}" method="POST"
                                                  action="{{ route('users.destroy', $user) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->first_name . ' ' . $user->last_name) }}')"
                                                        class="admin-btn-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span style="color:#888;font-size:0.85rem;font-style:italic;">You</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-table"><p>No users found.</p></div>
                @endif
                <div class="pagination-wrapper">
                    {{ $users->links('pagination.admin', ['pageLink' => 'users']) }}
                </div>
            </div>
        </section>

    </div>

    <script>
        const csrfToken = '{{ csrf_token() }}';

        function clearField() {
            document.getElementById('image').value = '';
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Delete ' + name + '?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        function toggleCreateUserForm() {
            const form = document.getElementById('createUserForm');
            const btn = document.getElementById('createUserToggle');
            if (!form) return;
            if (form.style.display === 'none') {
                form.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-times"></i> Cancel';
            } else {
                form.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-user-plus"></i> Add User';
            }
        }

        function toggleCreateDiscountForm() {
            const form = document.getElementById('createDiscountForm');
            const btn = document.getElementById('createDiscountToggle');
            if (!form) return;
            if (form.style.display === 'none') {
                form.style.display = 'block';
                btn.innerHTML = '<i class="fas fa-times"></i> Cancel';
            } else {
                form.style.display = 'none';
                btn.innerHTML = '<i class="fas fa-plus"></i> Add Discount';
            }
        }

        function confirmDeleteUser(id, name) {
            Swal.fire({
                title: 'Delete ' + name + '?',
                text: 'This will permanently delete their account.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('delete-user-form-' + id).submit();
                }
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
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(() => {
                    const row = document.getElementById('review-row-' + id);
                    if (row) row.remove();
                })
                .catch(() => Swal.fire('Error', 'Could not delete the review.', 'error'));
            });
        }
    </script>
</x-admin-layout>
