<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Gadya CMS housekeeping. The server needs `php artisan schedule:run`
 * every minute; gadya/connect's check-in rides the same scheduler.
 */
Schedule::command('gadya-cms:publish-due')->everyFiveMinutes();
Schedule::command('gadya-cms:prune-trash')->daily();
Schedule::command('gadya-cms:check-links')->weeklyOn(2, '03:00');
Schedule::command('gadya-cms:prune-activity')->weekly();
Schedule::command('gadya-cms:prune-analytics')->weeklyOn(1, '03:00');
Schedule::command('gadya-cms:analytics-digest')->weeklyOn(1, '08:00');
Schedule::command('gadya-cms:search-console')->dailyAt('05:00');
Schedule::command('gadya-cms:pagespeed')->weeklyOn(2, '04:00');

