@if(auth()->check())
    @include('products.partials.auth-store')
@else
    @include('products.partials.guest-store')
@endif
