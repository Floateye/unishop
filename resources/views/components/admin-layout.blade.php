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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="admin-body">
<header class="admin-header">
    <div class="nav">
        <div class="logo"><i class="fas fa-shield-alt"></i> UniShop Admin</div>
        <ul class="nav-links">
            <li><a href="{{ route('dashboard') }}#dashboard"><i class="fas fa-chart-pie"></i> Overview</a></li>
            <li><a href="#add-product"><i class="fas fa-plus"></i> New Product</a></li>
            <li><a href="#manage"><i class="fas fa-warehouse"></i> Inventory</a></li>
            <li><a href="#orders"><i class="fas fa-file-invoice-dollar"></i> Orders</a></li>
            <li><a href="#discounts"><i class="fas fa-tag"></i> Discounts</a></li>
            <li><a href="#reviews"><i class="fas fa-star"></i> Reviews</a></li>
            <li><a href="#users"><i class="fas fa-users-cog"></i> Users</a></li>
            <li><a href="{{ route('products.index') }}"><i class="fas fa-store"></i> Visit Shop</a></li>
        </ul>
    </div>
</header>

{{$slot}}
</body>
</html>
