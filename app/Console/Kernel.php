<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        '\App\Console\Commands\CleanOldSessions',
    ];

    protected function schedule(Schedule $schedule)	{
		$schedule -> command( 'oldsessions:remove' ) -> twiceDaily( 1, 16 ) ;
													}

    protected function commands()	{
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
									}
}
