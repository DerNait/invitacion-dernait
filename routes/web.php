<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\RsvpAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

// Invitación pública
Route::get('/', [InvitationController::class, 'show'])->name('invitation');
Route::get('/invitacion.ics', [InvitationController::class, 'calendar'])->name('calendar');
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');

// Autenticación del anfitrión
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// Panel de confirmaciones (protegido)
Route::middleware('auth')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/export', [ExportController::class, 'guests'])->name('admin.export');
    Route::delete('/admin/rsvps/{rsvp}', [RsvpAdminController::class, 'destroy'])->name('admin.rsvps.destroy');
    Route::post('/admin/rsvps/{rsvp}/restore', [RsvpAdminController::class, 'restore'])->name('admin.rsvps.restore');
});
