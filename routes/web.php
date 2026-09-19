<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
 * The public site is data: every page lives in the Gadya CMS site document
 * and renders through one controller. Any new top-level route must also be
 * listed in gadya-cms.pages.route_excluded_slugs, or this one swallows it.
 */
Route::get('/', [SiteController::class, 'show'])->name('home');

Route::get('/{slug}', [SiteController::class, 'show'])
    ->where('slug', '(?!'.implode('$|', (array) config('gadya-cms.pages.route_excluded_slugs', ['admin', 'cms', 'livewire', 'storage', 'up'])).'$)[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('pages.show');
