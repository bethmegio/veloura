protected $routeMiddleware = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'shop.owner' => \App\Http\Middleware\ShopOwnerMiddleware::class,
    'customer' => \App\Http\Middleware\CustomerMiddleware::class,
];