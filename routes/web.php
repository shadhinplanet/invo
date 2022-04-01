<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Welcome view
Route::get('/', function () {

    return view('welcome');
});


// Backend routes
Route::prefix('dashboard')->middleware(['auth','verified'])->group(function () {

    // Dashboard
    Route::get('/', function () {
        // Get current user
        $user = User::find(Auth::user()->id);
        // return response
        return view('dashboard')->with([
            'user'               => $user,
            'activity_logs'      => ActivityLog::where('user_id',Auth::id())->latest()->get(),
            'pending_tasks'      => $user->tasks->where('status',    'pending'),
            'unpaid_invoices'    => $user->invoices->where('status', 'unpaid'),
            'paid_invoices'      => $user->invoices->where('status', 'paid'),
        ]);
    })->name('dashboard');

    // User Route
    Route::resource('user', UserController::class);

    // Client Route
    Route::resource('client', ClientController::class);

    // Task Route
    Route::resource('task', TaskController::class);
    Route::put('task/{task}/complete', [TaskController::class, 'markAsComplete'])->name('markAsComplete');

    // Invoices Route
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::put('{invoice}/update', [InvoiceController::class, 'update'])->name('invoice.update');
        Route::delete('{invoice}/delete', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
        Route::get('inovice', [InvoiceController::class, 'inovice'])->name('inovice');
        Route::get('email/send/{invoice:invoice_id}', [InvoiceController::class, 'sendEmail'])->name('invoice.sendEmail');
    });

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/update', [SettingsController::class, 'update'])->name('settings.update');
});

// Auth Routes
require __DIR__ . '/auth.php';
