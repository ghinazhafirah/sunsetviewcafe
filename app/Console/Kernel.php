<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Daftarkan command artisan kamu.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Jadwalkan perintah Artisan.
     */
    protected function schedule(Schedule $schedule)
    {
        // Tambahkan command expire yang tadi kamu buat
        $schedule->command('orders:expire')->everyMinute();
    }
}
