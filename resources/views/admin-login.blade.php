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
    <h1>IAU UniShop</h1>
    <h2>Admin Login</h2>

    <form onsubmit="handleAdminLogin(event)">
        <div>
            <label for="username">Username</label><br>
            <input type="text" id="username" name="username" required>
        </div>
        <br>
        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>

    <br>
    <button onclick="window.location.href='{{ route('welcome') }}'">Back</button>

    <script>
        function handleAdminLogin(e) {
            e.preventDefault();
            window.location.href = '{{ route('admin') }}';
        }
    </script>
</body>
</html>
