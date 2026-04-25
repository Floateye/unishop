<x-admin-layout>
    <x-status/>
    <div class="admin-container"> <!-- the container -->

        <section id="dashboard" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-chart-pie"></i> Operation Overview</h2>
                <p>High-level metrics corresponding to the Products, Orders, and Users models. :p</p>
            </div>
            <div class="admin-stats-grid"> <!-- stats! and other stuff -->
                <div class="admin-stat-card">
                    <i class="fas fa-box-open"></i>
                    <div class="admin-stat-info">
                        <h3>Total Products</h3>
                        <strong>{{$totalProducts}}</strong>
                    </div>
                </div> <!-- inline was more convenient for this specific kind of styling... -->
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

        <!-- here we have forms to add our products!!!! -->
        <section id="add-product" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-folder-plus"></i> Add New Product</h2>
                <p>Register a new item into the inventory. This form mimics the structure required by the <b>Product</b> eloquent model (`name`, `slug`, `category_id`, `description`, `image`, `price`).</p>
            </div>
            <div class="divider"></div>
            <div class="admin-form-card">
                <form id="add-product-form" method="post" action="{{route('products.store')}}" class="auth-form" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="auth-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li >{{ $error }}</li>
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
                        <label>Image URL / Path</label>
                        <div class="image-label">
                            <input type="file" id="image"  name="image" required>
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
                        <label for="description">Detailed Description</label>
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

        <!-- our items!!! -->
        <section id="manage" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-warehouse"></i> Inventory Management</h2>
                <p>View all stored Product models.</p>
            </div>
            <div class="divider"></div>
            <div class="admin-table-wrapper">
                @if($products)
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Slug</th>
                            <th>Category ID</th>
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
                                    <a href="{{ route('products.show', $product) }}" class="admin-btn-view"> <i class="fas fa-eye"></i>View</a>

                                    <form id="delete-form-{{ $product->id }}"
                                          method="POST"
                                          action="{{ route('products.destroy', $product) }}"
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')"
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
                        <p>There is no products</p>
                    </div>
                @endif

                <div class="pagination-wrapper">
                    {{ $products->links('pagination.admin',['pageLink' =>'manage' ])}}
                </div>
            </div>
        </section>

        <!-- Current orders! placeholders of course... -->
        <section id="orders" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-file-invoice-dollar"></i> Customer Orders</h2>
                <p>View all <b>Order</b> models containing user relations and complex JSON snapshots.</p>
            </div>
            <div class="divider"></div>
            <div class="admin-table-wrapper">
                @if($orders->isNotEmpty())
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User Name</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Shipping Details (Snapshot)</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{$order->id}}</td>
                                <td>{{$order->user->name}}</td>
                                <td>{{$order->total_amount}} SAR</td>
                                <td><span class="admin-badge {{$order->payment_status === 'paid' ? "badge-success":"badge-warning"}}">{{$order->payment_status}}</span></td>
                                <td><button class="admin-btn-view"><i class="fas fa-eye"></i> View JSON</button></td>
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
                    <div class="empty-table">
                        <p>There is no orders</p>
                    </div>
                @endif
                    <div class="pagination-wrapper">
                        {{ $orders->links('pagination.admin',['pageLink' => 'orders']) }}
                    </div>
            </div>
        </section>
        <section id="discounts" class="admin-section">

            <div class="header-section" >
                <h1>Discounts</h1>
                <a href="{{route('discount.create')}}" class="link">Create New Discount</a>
            </div>
            <div class="divider"></div>
            @if($discounts->isNotEmpty())
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Discount Rate</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($discounts as $discount)
                        <tr>
                            <td>{{$discount->id}}</td>
                            <td>{{$discount->code}}</td>
                            <td>{{$discount->rate * 100}}%</td>
                            <td>{{$discount->starts_at->format('M j, Y g:i A')}}</td>
                            <td>{{$discount->expires_at->format('M j, Y g:i A')}}</td>
                            <td class="admin-tbl-actions">
                            <td class="admin-tbl-actions">
                                <a href="" class="admin-btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="" class="admin-btn-view"> <i class="fas fa-eye"></i>View</a>

                                <form id="delete-form"
                                      method="POST"
                                      action=""
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
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
                    <p>No Discounts</p>
                </div>
            @endif
            <div class="pagination-wrapper">
                {{ $discounts->links('pagination.admin', ['pageLink' => 'discounts']) }}
            </div>
        </section>
    </div>
    <script>
        function clearField() {
            document.getElementById('image').value = '';
        }
        function confirmDelete(id,name){
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
            })
        }
    </script>
</x-admin-layout>
