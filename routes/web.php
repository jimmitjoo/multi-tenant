<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/accent-invitation/{token}', function ($token) {
    $invitation = App\Models\OrganizationInvitation::where('token', $token)->firstOrFail();

    if ($invitation->hasExpired()) {
        return redirect()->to('/admin')->withErrors([
            'email' => __('This invitation has expired.'),
        ]);
    }

    if ($invitation->email !== auth()->user()->email) {
        return redirect()->to('/admin')->withErrors([
            'email' => __('This invitation is not valid.'),
        ]);
    }

    $invitation->accept();

    return redirect()->to(\App\Filament\Pages\Dashboard::getRoutePath());
})->middleware(['auth'])->name('organization.accept-invitation');
