<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan Command Payout Mentor setiap tanggal 10 jam 01:00 AM
Schedule::command('mentor:payout')->monthlyOn(10, '01:00');
