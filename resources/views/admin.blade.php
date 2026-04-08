<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAU UniShop</title>
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <script src="{{ asset('assets/main.js') }}"></script>
</head>
<body>

    <header>
        <h1>IAU UniShop — Admin Panel</h1>
        <nav>
            <a href="{{ route('store') }}">View Store</a> |
            <a href="{{ route('welcome') }}">Logout</a>
        </nav>
    </header>

    <hr>

    <!-- Dashboard Summary -->
    <section id="dashboard">
        <h2>Dashboard</h2>
        <p>Total Products: <strong id="total-products">0</strong></p>
        <p>Total Orders: <strong id="total-orders">0</strong></p>
        <p>Total Users: <strong id="total-users">0</strong></p>
    </section>

    <hr>

    <!-- Add New Product -->
    <section id="add-product">
        <h2>Add New Product</h2>
        <form id="add-product-form" onsubmit="return false;">
            <div>
                <label for="product-name">Product Name</label><br>
                <input type="text" id="product-name" name="product-name" placeholder="e.g. IAU Hoodie" required>
            </div>
            <br>
            <div>
                <label for="product-category">Category</label><br>
                <select id="product-category" name="product-category" required>
                    <option value="">Select Category</option>
                    <option value="clothes">Clothes</option>
                    <option value="accessories">Accessories</option>
                    <option value="souvenirs">Souvenirs</option>
                </select>
            </div>
            <br>
            <div>
                <label for="product-price">Price ($)</label><br>
                <input type="number" id="product-price" name="product-price" min="0" step="0.01" placeholder="0.00" required>
            </div>
            <br>
            <div>
                <label for="product-stock">Stock Quantity</label><br>
                <input type="number" id="product-stock" name="product-stock" min="0" placeholder="0" required>
            </div>
            <br>
            <div>
                <label for="product-description">Description</label><br>
                <textarea id="product-description" name="product-description" rows="3" cols="40" placeholder="Product description..."></textarea>
            </div>
            <br>
            <div>
                <label for="product-image">Image URL</label><br>
                <input type="text" id="product-image" name="product-image" placeholder="https://...">
            </div>
            <br>
            <button type="submit" id="add-product-btn">Add Product</button>
            <button type="reset">Clear</button>
        </form>
    </section>

    <hr>

    <!-- Product List -->
    <section id="product-list">
        <h2>All Products</h2>

        <div>
            <input type="text" id="search-products" placeholder="Search products...">
            <select id="filter-category">
                <option value="all">All Categories</option>
                <option value="clothes">Clothes</option>
                <option value="accessories">Accessories</option>
                <option value="souvenirs">Souvenirs</option>
            </select>
            <button id="search-btn">Search</button>
            <button id="reset-filter-btn">Reset</button>
        </div>

        <br>

        <table border="1" id="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="products-table-body">
                <!-- Example rows — to be populated by JS -->
                <tr id="product-row-1">
                    <td>1</td>
                    <td><img src="" alt="product image" width="50"></td>
                    <td id="name-1">IAU Hoodie</td>
                    <td id="category-1">Clothes</td>
                    <td id="price-1">$29.99</td>
                    <td id="stock-1">15</td>
                    <td>
                        <button onclick="editProduct(1)">Edit</button>
                        <button onclick="deleteProduct(1)">Delete</button>
                    </td>
                </tr>
                <tr id="product-row-2">
                    <td>2</td>
                    <td><img src="" alt="product image" width="50"></td>
                    <td id="name-2">IAU Mug</td>
                    <td id="category-2">Souvenirs</td>
                    <td id="price-2">$12.99</td>
                    <td id="stock-2">40</td>
                    <td>
                        <button onclick="editProduct(2)">Edit</button>
                        <button onclick="deleteProduct(2)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <hr>

    <!-- Edit Product Form (hidden until edit is clicked) -->
    <section id="edit-product" hidden>
        <h2>Edit Product</h2>
        <form id="edit-product-form" onsubmit="return false;">
            <input type="hidden" id="edit-product-id" name="edit-product-id">
            <div>
                <label for="edit-product-name">Product Name</label><br>
                <input type="text" id="edit-product-name" name="edit-product-name" required>
            </div>
            <br>
            <div>
                <label for="edit-product-category">Category</label><br>
                <select id="edit-product-category" name="edit-product-category" required>
                    <option value="">Select Category</option>
                    <option value="clothes">Clothes</option>
                    <option value="accessories">Accessories</option>
                    <option value="souvenirs">Souvenirs</option>
                </select>
            </div>
            <br>
            <div>
                <label for="edit-product-price">Price ($)</label><br>
                <input type="number" id="edit-product-price" name="edit-product-price" min="0" step="0.01" required>
            </div>
            <br>
            <div>
                <label for="edit-product-stock">Stock Quantity</label><br>
                <input type="number" id="edit-product-stock" name="edit-product-stock" min="0" required>
            </div>
            <br>
            <div>
                <label for="edit-product-description">Description</label><br>
                <textarea id="edit-product-description" name="edit-product-description" rows="3" cols="40"></textarea>
            </div>
            <br>
            <div>
                <label for="edit-product-image">Image URL</label><br>
                <input type="text" id="edit-product-image" name="edit-product-image">
            </div>
            <br>
            <button type="submit" id="save-edit-btn">Save Changes</button>
            <button type="button" id="cancel-edit-btn" onclick="document.getElementById('edit-product').hidden=true">Cancel</button>
        </form>
    </section>

    <hr>

    <!-- Orders Section -->
    <section id="orders">
        <h2>Orders</h2>
        <table border="1" id="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
                <!-- Example row — to be populated by JS -->
                <tr>
                    <td>#1001</td>
                    <td>John Doe</td>
                    <td>IAU Hoodie x1</td>
                    <td>$29.99</td>
                    <td>
                        <select>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </td>
                    <td>
                        <button onclick="viewOrder(1001)">View</button>
                        <button onclick="deleteOrder(1001)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <hr>

    <footer>
        <p>&copy; 2026 IAU UniShop Admin Panel. All rights reserved.</p>
    </footer>

</body>
</html>
