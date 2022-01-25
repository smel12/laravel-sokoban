<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class GetControls extends Controller
{

 	public function captureControls( Request $request )	{
		$user = auth() -> user() ;
		$direction = request() -> get( 'Direction' ) ;
		if ( isset( $direction ) )	{
			if ( $user != Null )	{
				$usersTable = 'game-users' ;
				$userId = $user -> id ;
				$userEmail = $user -> email ;
					DB::table( $usersTable ) -> where( 'id', $userId ) -> update( [ 'control' => $direction ] ) ;
									}
			else	{
				$sessionsTable = 'game-sessions' ;
				$sessionId = session() -> getId() ;
					DB::table( $sessionsTable ) -> where( 'id', $sessionId ) -> update( [ 'control' => $direction ] ) ;
					} ;
									} ;
		$previousUrl = url() -> previous() ;
		$currentUrl = url() -> current() ;
		if ( $currentUrl === $previousUrl ) return redirect( 'game' ) ;
		$urlArr = parse_url( $previousUrl ) ;
		return redirect( mb_substr( $urlArr['path'], 1 ) ) ;
														}

}
