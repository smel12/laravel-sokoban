<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanOldSessions extends Command
{
    protected $signature = 'oldsessions:remove';
    protected $description = 'Remove all overdue sessions in "game-sessions" table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
			$allSessions = DB::table( 'game-sessions' ) -> select( 'id' ) -> get() ;
			foreach( $allSessions as $session )	{
				$name = $session -> id ;
				if ( DB::table( 'sessions' ) -> where( 'id', $name ) -> doesntExist() )	{
					DB::table( 'game-sessions' ) -> where( 'id', $name ) -> delete() ;
																						} ;
												} ;
        return Command::SUCCESS;
    }
}
