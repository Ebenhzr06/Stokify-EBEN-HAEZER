<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Categorycontroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductAtributcontroller;
use App\Http\Controllers\Productcontroller;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\Stocktransactioncontroller;
use App\Http\Controllers\Suppliercontroller;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockOpnameController;
// Hapus impor model yang tidak diperlukan di sini:
// use App\Models\Atribut;
// use App\Models\Category;
// use App\Models\ProductAtribute;
// use App\Models\StockTransaction;
// use App\Models\Supplier;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute untuk autentikasi (Login, Signup, Logout)
Route::controller(AuthController::class)->group(function () {
    Route::get('signup', 'signup')->name('signup');
    Route::post('signup', 'signupSimpan')->name('signup.simpan');
    Route::get('/', 'login')->name('login');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAksi')->name('login.aksi');
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

// Grup rute untuk peran Admin
Route::group(['middleware' => 'Admin'], function () {
    Route::prefix('Admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('Admin.index');

        // Rute untuk User Management (khusus Admin)
        Route::controller(Usercontroller::class)->prefix('users')->group(function () {
            Route::get('', 'index')->name('users.index');
            Route::get('create', 'create')->name('users.create');
            Route::post('', 'store')->name('users.store');
            Route::get('{user}/edit', 'edit')->name('users.edit');
            Route::put('{user}', 'update')->name('users.update');
            Route::delete('{user}', 'destroy')->name('users.destroy');
        });

        // Rute untuk Product (Admin)
        Route::controller(Productcontroller::class)->prefix('product')->group(function () {
            Route::get('', 'products')->name('Admin.product.index'); // Diubah namanya
            Route::get('tambah', 'tambah')->name('product.tambah');
            Route::post('tambah', 'simpan')->name('product.tambah.simpan');
            Route::get('edit/{id}', 'edit')->name('product.edit');
            Route::put('{id}', 'update')->name('product.update'); // Diubah metode & nama
            Route::delete('{id}', 'hapus')->name('product.hapus'); // Diubah metode
            Route::get('export', 'export')->name('product.export'); // Perbaiki prefix
            Route::post('import', 'import')->name('product.import'); // Perbaiki prefix
        });

        // Rute untuk Category (Admin)
        Route::controller(Categorycontroller::class)->prefix('category')->group(function () {
            Route::get('', 'categorys')->name('Admin.category.index'); // Diubah namanya
            Route::get('tambah', 'tambah')->name('category.tambah');
            Route::post('tambah', 'simpan')->name('category.tambah.simpan');
            Route::get('edit/{id}', 'edit')->name('category.edit');
            Route::put('{id}', 'update')->name('category.update'); // Diubah metode & nama
            Route::delete('{id}', 'hapus')->name('category.hapus'); // Diubah metode
        });

        // Rute untuk Supplier (Admin)
        Route::controller(Suppliercontroller::class)->prefix('supplier')->group(function () {
            Route::get('', 'suppliers')->name('Admin.supplier.index'); // Diubah namanya
            Route::get('tambah', 'tambah')->name('supplier.tambah');
            Route::post('tambah', 'simpan')->name('supplier.tambah.simpan');
            Route::get('edit/{id}', 'edit')->name('supplier.edit');
            Route::put('{id}', 'update')->name('supplier.update'); // Diubah metode & nama
            Route::delete('{id}', 'hapus')->name('supplier.hapus'); // Diubah metode
        });

        // Rute untuk Product Atribut (Admin)
        Route::controller(ProductAtributcontroller::class)->prefix('atribut')->group(function () {
            Route::get('', 'productatributs')->name('Admin.atribut.index'); // Diubah namanya
            Route::get('tambah', 'tambah')->name('atribut.tambah');
            Route::post('tambah', 'simpan')->name('atribut.tambah.simpan');
            Route::get('edit/{id}', 'edit')->name('atribut.edit');
            Route::put('{id}', 'update')->name('atribut.update'); // Diubah metode & nama
            Route::delete('{id}', 'hapus')->name('atribut.hapus'); // Diubah metode
        });

        // Rute untuk Stock Transaction (Admin)
        Route::controller(Stocktransactioncontroller::class)->prefix('stock')->group(function () {
            Route::get('', 'stocks')->name('Admin.stock.index'); // Diubah namanya
            Route::get('tambah', 'tambah')->name('stock.tambah');
            Route::post('tambah', 'simpan')->name('stock.tambah.simpan');
            Route::get('edit/{id}', 'edit')->name('stock.edit');
            Route::put('{id}', 'update')->name('stock.update'); // Diubah metode & nama
            Route::delete('{id}', 'hapus')->name('stock.hapus'); // Diubah metode
        });

        // Rute untuk Stock Opname (Admin)
        Route::controller(StockOpnameController::class)->prefix('opname')->group(function () {
            Route::get('', 'index')->name('Admin.opname.index'); // Diubah namanya
            Route::put('{id}', 'update')->name('opname.update'); // Diubah metode
        });

        // Rute untuk Settings (Admin)
        Route::controller(SettingController::class)->prefix('settings')->group(function () {
            Route::get('', 'index')->name('settings.index');
            Route::post('', 'updateLogo')->name('settings.update');
            Route::post('app-name', 'updateAppName')->name('settings.app_name.update');
        });

        // Rute untuk Laporan (Admin)
        Route::controller(LaporanController::class)->prefix('laporan')->group(function () {
            Route::get('stok', 'stok')->name('Admin.laporan.stock'); // Diubah namanya
            Route::get('transaksi', 'transaksi')->name('Admin.laporan.transaksi'); // Diubah namanya
            Route::get('user', 'user')->name('Admin.laporan.user'); // Diubah namanya
        });
    });
});

// Grup rute untuk peran Manajer Gudang
Route::group(['middleware' => 'Manajer_gudang'], function () {
    Route::prefix('Manajergudang')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('Manajer.index');

        // Rute untuk Product (Manajer Gudang) - Hanya tampilan index
        Route::controller(Productcontroller::class)->prefix('product')->group(function () {
            Route::get('', 'products')->name('Manajer.product.index'); // Diubah namanya
            Route::get('{id}', 'show')->name('Manajer.product.show');
        });

        // Rute untuk Supplier (Manajer Gudang) - Hanya tampilan index
        Route::controller(Suppliercontroller::class)->prefix('supplier')->group(function () {
            Route::get('', 'suppliers')->name('Manajer.supplier.index'); // Diubah namanya
        });

        // Rute untuk Stock Transaction (Manajer Gudang)
        Route::controller(Stocktransactioncontroller::class)->prefix('stock')->group(function () {
            Route::get('', 'stocks')->name('Manajer.stock.index'); // Diubah namanya
            Route::get('tambah', 'tambah')->name('Manajer.stock.tambah'); // Diubah namanya
            Route::post('tambah', 'simpan')->name('Manajer.stock.tambah.simpan'); // Diubah namanya
            Route::get('edit/{id}', 'edit')->name('Manajer.stock.edit'); // Diubah namanya
            Route::put('{id}', 'update')->name('Manajer.stock.update'); // Diubah metode & nama
            Route::delete('{id}', 'hapus')->name('Manajer.stock.hapus'); // Diubah metode
        });

        // Rute untuk Stock Opname (Manajer Gudang)
        Route::controller(StockOpnameController::class)->prefix('opname')->group(function () {
            Route::get('', 'index')->name('Manajer.opname.index'); // Diubah namanya
            Route::put('{id}', 'update')->name('Manajer.opname.update'); // Diubah metode & nama
        });

        // Rute untuk Laporan (Manajer Gudang)
        Route::controller(LaporanController::class)->prefix('laporan')->group(function () {
            Route::get('stok', 'stok')->name('Manajer.laporan.stock'); // Diubah namanya
            Route::get('transaksi', 'transaksi')->name('Manajer.laporan.transaksi'); // Diubah namanya
        });
    });
});

Route::group(['middleware' => 'Staff'], function () {
    Route::prefix('Staff')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('Staff.index');
        Route::get('stok',[LaporanController::class,'stok'])->name('Staff.stock.index'); // Diubah namanya
    });

     Route::prefix('stock')->name('stock.')->group(function () {
        Route::post('/{stockTransaction}/confirm', [Stocktransactioncontroller::class, 'confirm'])->name('confirm');
        Route::post('/{stockTransaction}/reject', [Stocktransactioncontroller::class, 'reject'])->name('reject');
        Route::post('/{stockTransaction}/remove', [Stocktransactioncontroller::class, 'remove'])->name('remove');
    });
});


