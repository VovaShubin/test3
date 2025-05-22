<nav>
    <ul>
        <li class="{{ Route::currentRouteName() == 'home' ? 'active' : '' }}">
            <a href="{{ route('home') }}">Home</a>
        </li>
        <li class="{{ Route::currentRouteName() == 'product' ? 'active' : '' }}">
            <a href="{{ route('product.index') }}">Product</a>
        </li>
        <li class="{{ Route::currentRouteName() == 'order' ? 'active' : '' }}">
            <a href="{{ route('order.index') }}">Order</a>
        </li>
    </ul>
</nav>
