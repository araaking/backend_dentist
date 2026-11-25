<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Bersihkan token Sanctum yang sudah kedaluwarsa. Butuh cron `schedule:run` (lihat README).
Schedule::command('sanctum:prune-expired --hours=24')->daily();
