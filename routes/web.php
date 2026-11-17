<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HouseTypeController as AdminHouseTypeController;
use App\Http\Controllers\Admin\KprBankController as AdminKprBankController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SiteplanMapperController;
use App\Http\Controllers\Admin\UnitController as AdminUnitController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KprController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/perumahan', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/perumahan/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/perumahan-di-{city}', [ProjectController::class, 'cityLanding'])->name('projects.city');

Route::get('/simulasi-kpr', [KprController::class, 'index'])->name('kpr.index');
Route::post('/booking-survei', [BookingController::class, 'store'])->name('booking.store');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/tentang-kami', [PageController::class, 'about'])->name('pages.about');
Route::get('/kontak', [PageController::class, 'contact'])->name('pages.contact');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Panel Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Projects CRUD
    Route::resource('projects', AdminProjectController::class);

    // House Types per Project
    Route::resource('projects.house-types', AdminHouseTypeController::class)->except(['show']);

    // Units / Kavlings per Project (dengan koordinat siteplan & POV)
    Route::resource('projects.units', AdminUnitController::class)->except(['show']);

    // Siteplan Visual Mapper & Slicer Tool
    Route::get('projects/{project}/siteplan-mapper', [SiteplanMapperController::class, 'index'])->name('projects.siteplan.index');
    Route::post('projects/{project}/siteplan-mapper/upload', [SiteplanMapperController::class, 'uploadImage'])->name('projects.siteplan.upload');
    Route::post('projects/{project}/siteplan-mapper/auto-slice', [SiteplanMapperController::class, 'autoSlice'])->name('projects.siteplan.auto-slice');
    Route::post('projects/{project}/siteplan-mapper/reset', [SiteplanMapperController::class, 'resetSlices'])->name('projects.siteplan.reset');
    Route::post('projects/{project}/siteplan-mapper/save', [SiteplanMapperController::class, 'saveSlice'])->name('projects.siteplan.save');

    // Bookings / Survey Leads
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'update', 'destroy']);

    // Settings (Dapat disesuaikan kapan saja oleh admin)
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // KPR Banks & Suku Bunga
    Route::resource('banks', AdminKprBankController::class)->only(['index', 'store', 'update', 'destroy']);

    // Blog Posts CRUD
    Route::resource('blog', AdminBlogPostController::class)->except(['show']);
});
