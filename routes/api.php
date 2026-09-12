<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\LowStockController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SalesReportController;
use App\Http\Controllers\Api\StockReportController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\ReceiptSettingController;
use App\Http\Controllers\Api\DeviceTokenController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post(
    '/login',
    [AuthController::class, 'login']
);


Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    );

    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    /*
    |--------------------------------------------------------------------------
    | DEVICE TOKEN / FCM
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/device-token',
        [DeviceTokenController::class, 'store']
    );

    Route::delete(
        '/device-token',
        [DeviceTokenController::class, 'destroy']
    );


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    );


    /*
    |--------------------------------------------------------------------------
    | LAPORAN PENJUALAN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/sales',
        [SalesReportController::class, 'index']
    );


    /*
    |--------------------------------------------------------------------------
    | LAPORAN STOK
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reports/stock',
        [StockReportController::class, 'index']
    );


    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN USER
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:owner,admin')->group(function () {

        Route::apiResource(
            '/users',
            UserController::class
        );

    });


    /*
    |--------------------------------------------------------------------------
    | PENGATURAN STRUK
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat pengaturan struk
    Route::get(
        '/receipt-settings',
        [ReceiptSettingController::class, 'show']
    );


    // Hanya Owner dan Admin yang boleh mengubah pengaturan struk
    Route::middleware('role:owner,admin')->group(function () {

        Route::put(
            '/receipt-settings',
            [ReceiptSettingController::class, 'update']
        );

        Route::patch(
            '/receipt-settings',
            [ReceiptSettingController::class, 'update']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat
    Route::get(
        '/categories',
        [CategoryController::class, 'index']
    );

    Route::get(
        '/categories/{category}',
        [CategoryController::class, 'show']
    );


    // Hanya Owner dan Admin yang boleh mengelola kategori
    Route::middleware('role:owner,admin')->group(function () {

        Route::post(
            '/categories',
            [CategoryController::class, 'store']
        );

        Route::put(
            '/categories/{category}',
            [CategoryController::class, 'update']
        );

        Route::patch(
            '/categories/{category}',
            [CategoryController::class, 'update']
        );

        Route::delete(
            '/categories/{category}',
            [CategoryController::class, 'destroy']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | JASA
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat
    Route::get(
        '/services',
        [ServiceController::class, 'index']
    );

    Route::get(
        '/services/{service}',
        [ServiceController::class, 'show']
    );


    // Owner, Admin, dan Kasir dapat mengelola jasa
    Route::middleware('role:owner,admin,kasir')->group(function () {

        Route::post(
            '/services',
            [ServiceController::class, 'store']
        );

        Route::put(
            '/services/{service}',
            [ServiceController::class, 'update']
        );

        Route::patch(
            '/services/{service}',
            [ServiceController::class, 'update']
        );

        Route::delete(
            '/services/{service}',
            [ServiceController::class, 'destroy']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | BARANG / PRODUK
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat
    Route::get(
        '/products',
        [ProductController::class, 'index']
    );

    Route::get(
        '/products/{product}',
        [ProductController::class, 'show']
    );


    // Owner, Admin, dan Kasir dapat mengelola barang
    Route::middleware('role:owner,admin,kasir')->group(function () {

        Route::post(
            '/products',
            [ProductController::class, 'store']
        );

        Route::put(
            '/products/{product}',
            [ProductController::class, 'update']
        );

        Route::patch(
            '/products/{product}',
            [ProductController::class, 'update']
        );

        Route::delete(
            '/products/{product}',
            [ProductController::class, 'destroy']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat customer
    Route::get(
        '/customers',
        [CustomerController::class, 'index']
    );

    Route::get(
        '/customers/{customer}',
        [CustomerController::class, 'show']
    );


    // Kasir, Admin, dan Owner dapat mengelola customer
    Route::middleware('role:owner,admin,kasir')->group(function () {

        Route::post(
            '/customers',
            [CustomerController::class, 'store']
        );

        Route::put(
            '/customers/{customer}',
            [CustomerController::class, 'update']
        );

        Route::patch(
            '/customers/{customer}',
            [CustomerController::class, 'update']
        );

        Route::delete(
            '/customers/{customer}',
            [CustomerController::class, 'destroy']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | KENDARAAN CUSTOMER
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat kendaraan
    Route::get(
        '/customers/{customer}/vehicles',
        [VehicleController::class, 'index']
    );

    Route::get(
        '/customers/{customer}/vehicles/{vehicle}',
        [VehicleController::class, 'show']
    );


    // Kasir, Admin, dan Owner dapat mengelola kendaraan
    Route::middleware('role:owner,admin,kasir')->group(function () {

        Route::post(
            '/customers/{customer}/vehicles',
            [VehicleController::class, 'store']
        );

        Route::put(
            '/customers/{customer}/vehicles/{vehicle}',
            [VehicleController::class, 'update']
        );

        Route::patch(
            '/customers/{customer}/vehicles/{vehicle}',
            [VehicleController::class, 'update']
        );

        Route::delete(
            '/customers/{customer}/vehicles/{vehicle}',
            [VehicleController::class, 'destroy']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT STOK
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat
    Route::get(
        '/stock-movements',
        [StockMovementController::class, 'index']
    );


    // Stok masuk dan keluar hanya Owner dan Admin
    Route::middleware('role:owner,admin')->group(function () {

        Route::post(
            '/stock-in',
            [StockMovementController::class, 'stockIn']
        );

        Route::post(
            '/stock-out',
            [StockMovementController::class, 'stockOut']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | LOW STOCK
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/low-stock',
        [LowStockController::class, 'index']
    );


    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/transactions',
        [TransactionController::class, 'index']
    );

    Route::get(
        '/transactions/{transaction}',
        [TransactionController::class, 'show']
    );


    // Kasir, Admin, dan Owner dapat membuat dan membatalkan transaksi
    Route::middleware('role:owner,admin,kasir')->group(function () {

        Route::post(
            '/transactions',
            [TransactionController::class, 'store']
        );

        Route::patch(
            '/transactions/{transaction}/cancel',
            [TransactionController::class, 'cancel']
        );

    });


    /*
    |--------------------------------------------------------------------------
    | PROJECT
    |--------------------------------------------------------------------------
    */

    // Semua user yang login boleh melihat project
    Route::get(
        '/projects',
        [ProjectController::class, 'index']
    );

    Route::get(
        '/projects/{project}',
        [ProjectController::class, 'show']
    );


    /*
    |--------------------------------------------------------------------------
    | KELOLA PROJECT
    |--------------------------------------------------------------------------
    */

    // Kasir, Admin, dan Owner dapat membuat dan mengelola project
    Route::middleware('role:owner,admin,kasir')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | PROJECT
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/projects',
            [ProjectController::class, 'store']
        );

        Route::put(
            '/projects/{project}',
            [ProjectController::class, 'update']
        );

        Route::patch(
            '/projects/{project}',
            [ProjectController::class, 'update']
        );

        Route::delete(
            '/projects/{project}',
            [ProjectController::class, 'destroy']
        );


        /*
        |--------------------------------------------------------------------------
        | PROJECT ITEMS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/projects/{project}/items',
            [ProjectController::class, 'storeItem']
        );

        // Edit quantity item project
        Route::put(
            '/projects/{project}/items/{item}',
            [ProjectController::class, 'updateItem']
        );

        Route::patch(
            '/projects/{project}/items/{item}',
            [ProjectController::class, 'updateItem']
        );

        Route::delete(
            '/projects/{project}/items/{item}',
            [ProjectController::class, 'destroyItem']
        );


        /*
        |--------------------------------------------------------------------------
        | PROJECT COSTS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/projects/{project}/costs',
            [ProjectController::class, 'storeCost']
        );

        Route::delete(
            '/projects/{project}/costs/{cost}',
            [ProjectController::class, 'destroyCost']
        );

        

    });

});

/*
|--------------------------------------------------------------------------
| DEBUG PHP ENVIRONMENT
|--------------------------------------------------------------------------
*/

Route::get('/debug/php', function () {
    return response()->json([
        'php_binary' => PHP_BINARY,
        'php_version' => PHP_VERSION,
        'php_ini' => php_ini_loaded_file(),

        'curl_cainfo' => ini_get('curl.cainfo'),
        'curl_cainfo_raw' => get_cfg_var('curl.cainfo'),

        'openssl_cafile' => ini_get('openssl.cafile'),
        'openssl_cafile_raw' => get_cfg_var('openssl.cafile'),

        'ssl_cert_file' => getenv('SSL_CERT_FILE'),

        'curl_version' => curl_version()['version'],
    ]);
});