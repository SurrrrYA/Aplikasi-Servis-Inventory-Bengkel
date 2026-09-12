<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

// =====================================================
// ADMIN CONTROLLERS
// =====================================================

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ReceiptSettingController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockReportController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProjectInvoiceController;


// =====================================================
// HOME
// =====================================================

Route::get('/', function () {
    return view('welcome');
});


// =====================================================
// AUTHENTICATION
// =====================================================

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'login']
    )->name('login.submit');
});


Route::post(
    '/logout',
    [LoginController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


// =====================================================
// ADMIN
// =====================================================

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // =================================================
        // DASHBOARD
        // =================================================

        Route::get(
            '/',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');


        // =================================================
        // LAPORAN
        // =================================================

        Route::get(
            '/reports',
            [ReportController::class, 'index']
        )->name('reports.index');


        // -------------------------------------------------
        // LAPORAN STOK
        // -------------------------------------------------

        Route::get(
            '/reports/stock',
            [StockReportController::class, 'index']
        )->name('reports.stock');


        // -------------------------------------------------
        // LAPORAN PENJUALAN
        // -------------------------------------------------

        Route::get(
            '/reports/sales',
            [SalesReportController::class, 'index']
        )->name('reports.sales');


        // =================================================
        // PENGATURAN STRUK
        // =================================================

        Route::get(
            '/receipt-settings',
            [ReceiptSettingController::class, 'edit']
        )->name('receipt-settings.edit');

        Route::put(
            '/receipt-settings',
            [ReceiptSettingController::class, 'update']
        )->name('receipt-settings.update');


        // =================================================
        // TRANSAKSI
        // =================================================

        /*
        |--------------------------------------------------------------------------
        | Admin hanya melihat transaksi.
        | Input transaksi dilakukan oleh Kasir.
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/transactions',
            [AdminTransactionController::class, 'index']
        )->name('transactions.index');

        Route::get(
            '/transactions/{transaction}',
            [AdminTransactionController::class, 'show']
        )->name('transactions.show');


        // =================================================
        // CUSTOMER
        // =================================================

        Route::get(
            '/customers',
            [CustomerController::class, 'index']
        )->name('customers.index');

        Route::get(
            '/customers/create',
            [CustomerController::class, 'create']
        )->name('customers.create');

        Route::post(
            '/customers',
            [CustomerController::class, 'store']
        )->name('customers.store');

        Route::get(
            '/customers/{customer}',
            [CustomerController::class, 'show']
        )->name('customers.show');

        Route::get(
            '/customers/{customer}/edit',
            [CustomerController::class, 'edit']
        )->name('customers.edit');

        Route::put(
            '/customers/{customer}',
            [CustomerController::class, 'update']
        )->name('customers.update');

        Route::delete(
            '/customers/{customer}',
            [CustomerController::class, 'destroy']
        )->name('customers.destroy');


        // =================================================
        // INVENTORY / PRODUK
        // =================================================

        Route::get(
            '/products',
            [ProductController::class, 'index']
        )->name('products.index');

        Route::get(
            '/products/create',
            [ProductController::class, 'create']
        )->name('products.create');

        Route::post(
            '/products',
            [ProductController::class, 'store']
        )->name('products.store');

        Route::get(
            '/products/{product}',
            [ProductController::class, 'show']
        )->name('products.show');

        Route::get(
            '/products/{product}/edit',
            [ProductController::class, 'edit']
        )->name('products.edit');

        Route::put(
            '/products/{product}',
            [ProductController::class, 'update']
        )->name('products.update');

        Route::delete(
            '/products/{product}',
            [ProductController::class, 'destroy']
        )->name('products.destroy');


        // =================================================
        // JASA SERVIS
        // =================================================

        Route::get(
            '/services',
            [ServiceController::class, 'index']
        )->name('services.index');

        Route::get(
            '/services/create',
            [ServiceController::class, 'create']
        )->name('services.create');

        Route::post(
            '/services',
            [ServiceController::class, 'store']
        )->name('services.store');

        Route::get(
            '/services/{service}',
            [ServiceController::class, 'show']
        )->name('services.show');

        Route::get(
            '/services/{service}/edit',
            [ServiceController::class, 'edit']
        )->name('services.edit');

        Route::put(
            '/services/{service}',
            [ServiceController::class, 'update']
        )->name('services.update');

        Route::delete(
            '/services/{service}',
            [ServiceController::class, 'destroy']
        )->name('services.destroy');


        // =================================================
        // ACTIVITY LOG
        // =================================================

        Route::get(
            '/activity-logs',
            [ActivityLogController::class, 'index']
        )->name('activity-logs.index');

        Route::get(
            '/activity-logs/{activityLog}',
            [ActivityLogController::class, 'show']
        )->name('activity-logs.show');


        // =================================================
        // MANAJEMEN USER
        // =================================================

        Route::get(
            '/users',
            [UserController::class, 'index']
        )->name('users.index');

        Route::get(
            '/users/create',
            [UserController::class, 'create']
        )->name('users.create');

        Route::post(
            '/users',
            [UserController::class, 'store']
        )->name('users.store');

        Route::get(
            '/users/{user}',
            [UserController::class, 'show']
        )->name('users.show');

        Route::get(
            '/users/{user}/edit',
            [UserController::class, 'edit']
        )->name('users.edit');

        Route::put(
            '/users/{user}',
            [UserController::class, 'update']
        )->name('users.update');


        // -------------------------------------------------
        // AKTIFKAN / NONAKTIFKAN USER
        // -------------------------------------------------

        Route::patch(
            '/users/{user}/toggle-status',
            [UserController::class, 'toggleStatus']
        )->name('users.toggle-status');


        // -------------------------------------------------
        // HAPUS USER
        // -------------------------------------------------

        Route::delete(
            '/users/{user}',
            [UserController::class, 'destroy']
        )->name('users.destroy');


        // =================================================
        // PROJECT
        // =================================================

        Route::get(
            '/projects',
            [AdminProjectController::class, 'index']
        )->name('projects.index');

        Route::get(
            '/projects/create',
            [AdminProjectController::class, 'create']
        )->name('projects.create');

        Route::post(
            '/projects',
            [AdminProjectController::class, 'store']
        )->name('projects.store');


        // -------------------------------------------------
        // INVOICE PROJECT
        // -------------------------------------------------

        Route::get(
            '/projects/{project}/invoice',
            [ProjectInvoiceController::class, 'download']
        )->name('projects.invoice');


        // -------------------------------------------------
        // DETAIL PROJECT
        // -------------------------------------------------

        Route::get(
            '/projects/{project}',
            [AdminProjectController::class, 'show']
        )->name('projects.show');

        Route::get(
            '/projects/{project}/edit',
            [AdminProjectController::class, 'edit']
        )->name('projects.edit');

        Route::put(
            '/projects/{project}',
            [AdminProjectController::class, 'update']
        )->name('projects.update');

        Route::delete(
            '/projects/{project}',
            [AdminProjectController::class, 'destroy']
        )->name('projects.destroy');


        // =================================================
        // PROJECT ITEMS
        // =================================================

        /*
        |--------------------------------------------------------------------------
        | Semua proses item project sekarang menggunakan
        | AdminProjectController karena kita sedang fokus
        | menyelesaikan business logic WEB terlebih dahulu.
        |--------------------------------------------------------------------------
        */


        // -------------------------------------------------
        // Tambah barang / jasa
        // -------------------------------------------------

        Route::post(
            '/projects/{project}/items',
            [AdminProjectController::class, 'storeItem']
        )->name('projects.items.store');


        // -------------------------------------------------
        // Update quantity barang / jasa
        // -------------------------------------------------

        Route::put(
            '/projects/{project}/items/{item}',
            [AdminProjectController::class, 'updateItem']
        )->name('projects.items.update');


        // -------------------------------------------------
        // Alternatif PATCH
        // -------------------------------------------------

        Route::patch(
            '/projects/{project}/items/{item}',
            [AdminProjectController::class, 'updateItem']
        )->name('projects.items.update.patch');


        // -------------------------------------------------
        // Hapus barang / jasa
        // -------------------------------------------------

        Route::delete(
            '/projects/{project}/items/{item}',
            [AdminProjectController::class, 'destroyItem']
        )->name('projects.items.destroy');


        // =================================================
        // PROJECT COSTS
        // =================================================

        /*
        |--------------------------------------------------------------------------
        | Biaya tambahan:
        | - Listrik
        | - Makan + Kopi
        | - Transportasi
        | - Marketing
        | - dll.
        |--------------------------------------------------------------------------
        */


        // -------------------------------------------------
        // Tambah biaya tambahan
        // -------------------------------------------------

        Route::post(
            '/projects/{project}/costs',
            [AdminProjectController::class, 'storeCost']
        )->name('projects.costs.store');


        // -------------------------------------------------
        // Hapus biaya tambahan
        // -------------------------------------------------

        Route::delete(
            '/projects/{project}/costs/{cost}',
            [AdminProjectController::class, 'destroyCost']
        )->name('projects.costs.destroy');

    });