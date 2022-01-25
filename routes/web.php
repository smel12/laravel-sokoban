<?php

use Illuminate\Support\Facades\Route;

Route::get( '/', function ()	{
	return redirect( '/game' ) ;
								} ) ;

Route::middleware('game.engine') -> group( function ()	{
	Route::match( [ 'get', 'post', 'head' ], '/game', function ()	{
		return view('gamescreen') ;
															} ) ;
														} ) ;

use App\Http\Controllers\GetControls ;
	Route::any( '/game/controls', [ GetControls::class, 'captureControls' ] ) ;

use App\Http\Controllers\SetLanguage ;
Route::get('/lang/{language}', [ SetLanguage::class, 'changeLanguage' ] ) ;

Route::middleware( ['auth:sanctum', 'verified'] ) -> get( '/dashboard', function ()	{
	return redirect( '/game' ) ;
																					} ) ;
