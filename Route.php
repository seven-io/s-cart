<?php
use App\Plugins\Other\Seven\Admin\AdminController;

Route::group(
    [
        'middleware' => SC_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Other\Seven\Admin',
        'prefix' => SC_ADMIN_PREFIX . '/seven',
    ],
    function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin_seven.index');
        Route::post('/', [AdminController::class, 'bulkSms'])->name('admin_seven.bulk_sms');
    }
);
