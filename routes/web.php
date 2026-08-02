<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');
Route::inertia('/werk', 'Work/Index')->name('work.index');
Route::inertia('/diensten/websites', 'Services/Websites')->name('services.websites');
Route::inertia('/diensten/dashboards', 'Services/Dashboards')->name('services.dashboards');
Route::inertia('/diensten/webshops', 'Services/Webshops')->name('services.webshops');
Route::inertia('/over-mij', 'About')->name('about');
Route::inertia('/contact', 'Contact')->name('contact.create');
Route::inertia('/privacy', 'Privacy')->name('privacy');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
    Route::delete('invitations/{invitation}', [TeamInvitationController::class, 'decline'])->name('invitations.decline');
});

require __DIR__.'/settings.php';
