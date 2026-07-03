<?php
// Add inside Route::prefix('admin')->name('admin.')->group(...) in web.php

Route::get('topuplist/export-pdf',   [WalletBalanceController::class, 'topuplist_export_pdf'])->name('topuplist_export_pdf');
Route::get('topuplist/export-excel', [WalletBalanceController::class, 'topuplist_export_excel'])->name('topuplist_export_excel');
