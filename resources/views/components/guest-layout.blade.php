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
    <script src="{{asset("assets/main.js")}}"></script>
</head>
<body>
{{$slot}}

<footer class="footer"> <!-- footer content :ppp -->
    <div class="footer-content">
        <div class="footer-links">
            <a href="#privacy">Privacy Policy</a>
            <a href="#tos">Terms of Service</a>
            <a href="#shipping">Shipping Policy</a>
            <a href="#contact">Contact Us!</a>
        </div>
        <p>&copy; 2026 IAU UniShop. All rights reserved.</p>
    </div>
</footer>

{{--<footer class="footer">--}}
{{--    <div class="footer-content">--}}
{{--        <p>&copy; 2026 IAU UniShop. All rights reserved.</p>--}}
{{--        <div class="footer-links">--}}
{{--            <a href="{{ route('store') }}">Store</a>--}}
{{--            <a href="{{ route('login') }}">User Login</a>--}}
{{--            <a href="{{ route('admin.login') }}">Admin Login</a>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</footer>--}}
</body>
</html>
