<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Middleware\GameEngine;

class SetLanguage extends Controller
{

 	public function changeLanguage( Request $request, $language )	{
		$user = auth() -> user() ;
		if ( $user != Null )	{
			$usersTable = 'game-users' ;
			$userId = $user -> id ;
			$userEmail = $user -> email ;
			if ( DB::table( $usersTable ) -> where( 'id', $userId ) -> exists() )	{
				DB::table( $usersTable ) -> where( 'id', $userId ) -> update( [ 'email' => $userEmail, 'language' => $language ] ) ;
																					}
			else	{
				DB::table( $usersTable ) -> insert( [ 'id' => $userId, 'email' => $userEmail, 'language' => $language, 'level' => 1, 'limit' => 1,
														'moves' => 0, 'control' => 'None', 'state' => GameEngine::gameLevel( 1 ) ] ) ;
					} ;
								}
		else	{
			$sessionsTable = 'game-sessions' ;
			$sessionId = session() -> getId() ;
			if ( DB::table( $sessionsTable ) -> where( 'id', $sessionId ) -> exists() )	{
				DB::table( $sessionsTable ) -> where( 'id', $sessionId ) -> update( [ 'language' => $language ] ) ;
																						}
			else	{
				DB::table( $sessionsTable ) -> insert( [ 'id' => $sessionId, 'language' => $language, 'level' => 1, 'limit' => 1,
														'moves' => 0, 'control' => 'None', 'state' => GameEngine::gameLevel( 1 ) ] ) ;
					} ;
				} ;
		$previousUrl = url() -> previous() ;
		$currentUrl = url() -> current() ;
		if ( $currentUrl === $previousUrl ) return redirect( 'game' ) ;
		$urlArr = parse_url( $previousUrl ) ;
		return redirect( mb_substr( $urlArr['path'], 1 ) ) ;
																	}

}
