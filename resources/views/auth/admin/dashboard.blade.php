<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IAU UniShop Admin Space</title>
    <link rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
</head>
<body class="admin-body">

    <header class="admin-header"> <!-- lets start!! -->
        <div class="nav">
            <div class="logo"><i class="fas fa-shield-alt"></i> UniShop Admin Hub</div>
            <ul class="nav-links">
                <li><a href="#dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="#add-product"><i class="fas fa-plus"></i> New Product</a></li>
                <li><a href="#manage"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li><a href="#orders"><i class="fas fa-receipt"></i> Orders</a></li>
                <li><a href="{{ route('store') ?? '#' }}"><i class="fas fa-store"></i> Visit Shop</a></li>
            </ul>
        </div>
    </header>

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
                        <strong>150</strong>
                    </div>
                </div> <!-- inline was more convenient for this specific kind of styling... -->
                <div class="admin-stat-card" style="background: linear-gradient(135deg, #108579 0%, #03342c 100%);">
                    <i class="fas fa-shopping-bag"></i>
                    <div class="admin-stat-info">
                        <h3>Total Orders</h3>
                        <strong>1,240</strong>
                    </div>
                </div>
                <div class="admin-stat-card" style="background: linear-gradient(135deg, #851042 0%, #340317 100%);">
                    <i class="fas fa-users"></i>
                    <div class="admin-stat-info">
                        <h3>Registered Users</h3>
                        <strong>450</strong>
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
            <div class="admin-form-card">
                <form id="add-product-form" onsubmit="return false;" class="auth-form">
                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" placeholder="e.g. Premium IAU Hoodie" required>
                        </div>
                        <div class="auth-field">
                            <label for="slug">Product Slug (URL structure)</label>
                            <input type="text" id="slug" name="slug" placeholder="e.g. premium-iau-hoodie" required>
                        </div>
                    </div>
                    
                    <div class="auth-field-split">
                        <div class="auth-field">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id" required>
                                <option value="">Select Category...</option>
                                <option value="1">Clothes</option>
                                <option value="2">Accessories</option>
                                <option value="3">Souvenirs</option>
                            </select>
                        </div>
                        <div class="auth-field">
                            <label for="price">Price (SAR)</label>
                            <input type="number" id="price" name="price" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="image">Image URL / Path</label>
                        <input type="text" id="image" name="image" placeholder="/assets/img/product.jpg" required>
                    </div>

                    <div class="auth-field">
                        <label for="description">Detailed Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Enter product details..." required></textarea>
                    </div>

                    <div class="admin-form-actions" style="margin-top: 1rem;"> 
                        <button type="button" class="auth-submit"><i class="fas fa-save"></i> Save Product to Database</button> <!-- to the database it goes! -->
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
            <div class="admin-table-wrapper">
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
                        <tr>
                            <td><img src="/assets/img/tshirt.webp" alt="Placeholder T-Shirt" class="admin-tbl-img" onerror="this.src='https://via.placeholder.com/60'"></td>
                            <td>Premium IAU T-Shirt</td>
                            <td>premium-iau-tshirt</td>
                            <td><span class="admin-badge badge-primary">1</span> (Clothes)</td>
                            <td>59.99 SAR</td>
                            <td class="admin-tbl-actions">
                                <button class="admin-btn-edit"><i class="fas fa-edit"></i> Edit</button>
                                <button class="admin-btn-delete"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td><img src="/assets/tshirt.webp" alt="Placeholder Mug" class="admin-tbl-img" onerror="this.src='https://via.placeholder.com/60'"></td>
                            <td>IAU Coffee Mug</td>
                            <td>iau-coffee-mug</td>
                            <td><span class="admin-badge badge-primary">3</span> (Souvenirs)</td>
                            <td>29.99 SAR</td>
                            <td class="admin-tbl-actions">
                                <button class="admin-btn-edit"><i class="fas fa-edit"></i> Edit</button>
                                <button class="admin-btn-delete"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Current orders! placeholders of course... -->
        <section id="orders" class="admin-section">
            <div class="admin-card-header">
                <h2><i class="fas fa-file-invoice-dollar"></i> Customer Orders</h2>
                <p>View all <b>Order</b> models containing user relations and complex JSON snapshots.</p>
            </div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Shipping Details (Snapshot)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#1040</td>
                            <td>User #42 (Jane Doe)</td>
                            <td>119.98 SAR</td>
                            <td><span class="admin-badge badge-success">Paid</span></td>
                            <td><button class="admin-btn-view"><i class="fas fa-eye"></i> View JSON</button></td>
                            <td class="admin-tbl-actions">
                                <button class="admin-btn-edit"><i class="fas fa-truck"></i> Update Status</button>
                            </td>
                        </tr>
                        <tr>
                            <td>#1041</td>
                            <td>User #15 (John Smith)</td>
                            <td>59.99 SAR</td>
                            <td><span class="admin-badge badge-warning">Unpaid</span></td>
                            <td><button class="admin-btn-view"><i class="fas fa-eye"></i> View JSON</button></td>
                            <td class="admin-tbl-actions">
                                <button class="admin-btn-edit"><i class="fas fa-truck"></i> Update Status</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>
