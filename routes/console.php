<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run queue worker — add this to cPanel cron:
// * * * * * cd /path-to-project && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();