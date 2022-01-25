@extends('layouts.gamelayout')
	@php
		$levelArr = request() -> get( 'levelArr' ) ;
		$currentLevel = $levelArr[0][0] ;
	@endphp

@section('title', __('messages.currentlevel').$currentLevel)

@section('header')
	@parent
	<div class="text-5xl font-bold text-blue-600 mt-1">Sokoban</div>
@endsection

@section('content')
	@parent
		<div class="m-auto">
			<table class="table-auto border-collapse bg-black font-mono text-5xl">
	@php
		$xSize = $levelArr[0][1] ;
		$ySize = $levelArr[0][2] ;
		$xCoord = $levelArr[0][3] ;
		$yCoord = $levelArr[0][4] ;
		$moves = $levelArr[0][5] ;
		$previousLevel = $levelArr[0][7] ;
		$nextLevel = $levelArr[0][8] ;
		$control = $levelArr[0][6] ;
		$winOfGame = $levelArr[0][9] ;
		for ( $y=1; $y<=$ySize; $y++ )	{
			if ( $y === 1 )	{
				echo '<tr class="leading-9 tracking-wide">' ;
							}
			else	{
				echo '<tr class="leading-9">' ;
					} ;
			for ( $x=1; $x<=$xSize; $x++ )	{
				$element = $levelArr[$y][$x] ;
				switch( $element )	{
					case 1 :	$gameElement = '<font face="entypo">&#x229f;</font>' ;
								$class = 'bg-blue-500 text-black text-3xl' ;
								$titleText = __('messages.warehousewall') ;
								$linkText = '#" onclick="return false>' ;
								break ;
					case 2 :	$gameElement = '<font face="entypo">&#x1f4e6;</font>' ;
								$class = 'text-red-500 text-2xl' ;
								$titleText = __('messages.container') ;
								$linkText = '#" onclick="return false>' ;
								break ;
					case 3 :	$gameElement = '+' ;
								$class = 'text-white text-3xl' ;
								$titleText = __('messages.placeforcontainer') ;
								$linkText = '#" onclick="return false>' ;
								break ;
					case 4 :	$gameElement = '<font face="entypo">&#x1f4e6;</font>' ;
								$class = 'text-green-500 text-2xl' ;
								$titleText = __('messages.containeronplace') ;
								$linkText = '#" onclick="return false>' ;
								break ;
					case 0 :	$gameElement = '' ;
								$class = 'text-black' ;
								$titleText = __('messages.emptyspace') ;
								$linkText = '#" onclick="return false>' ;
								break ;
									} ;
			if ( $xCoord === $x And $yCoord === $y )	{
				if ( $element === 3 )	{
					echo '<td class="text-yellow-300 text-2xl"><a href="'.$linkText.'" title="'.__('messages.loader').'" style="text-decoration:none"><font face="entypo">&#xe700;</font></a></td>' ;
										}
				else	{
					echo '<td class="text-yellow-300 text-2xl"><a href="'.$linkText.'" title="'.__('messages.loader').'" style="text-decoration:none"><font face="entypo">&#x1f464;</font></a></td>' ;
						} ;
														}
			else	{
				echo '<td class="'.$class.'"><a href="'.$linkText.'" title="'.$titleText.'" style="text-decoration:none">'.$gameElement.'</a></td>' ;
					} ;
											} ;
			echo '</tr>' ;
										} ;
		echo '</table>' ;
		echo '</div>' ;
		$levelAbstract = [] ;
		for ( $y=0; $y<=($ySize+1); $y++ )	{
			$levelAbstract[$y][0] = 1 ;
			$levelAbstract[$y][$xSize+1] = 1 ;
											} ;
		for ( $x=1; $x<=$xSize; $x++ )	{
			$levelAbstract[0][$x] = 1 ;
			$levelAbstract[$ySize+1][$x] = 1 ;
										} ;
		for ( $y=1; $y<=$ySize; $y++ )	{
			for ( $x=1; $x<=$xSize; $x++ )	{
				$element = $levelArr[$y][$x] ;
				switch( $element )	{
					case 1 :	$levelAbstract[$y][$x] = 1 ;
								break ;
					case 2 :	$levelAbstract[$y][$x] = 2 ;
								break ;
					case 3 :	$levelAbstract[$y][$x] = 0 ;
								break ;
					case 4 :	$levelAbstract[$y][$x] = 2 ;
								break ;
					case 0 :	$levelAbstract[$y][$x] = 0 ;
								break ;
									} ;
											} ;
										} ;
		$rightDirection = True ;
		if ( $levelAbstract[$yCoord][$xCoord+1] === 1 )	$rightDirection = False ;
		if ( $levelAbstract[$yCoord][$xCoord+1] === 2 And $levelAbstract[$yCoord][$xCoord+2] !== 0 )	$rightDirection = False ;
		$leftDirection = True ;
		if ( $levelAbstract[$yCoord][$xCoord-1] === 1 )	$leftDirection = False ;
		if ( $levelAbstract[$yCoord][$xCoord-1] === 2 And $levelAbstract[$yCoord][$xCoord-2] !== 0 )	$leftDirection = False ;
		$upDirection = True ;
		if ( $levelAbstract[$yCoord-1][$xCoord] === 1 )	$upDirection = False ;
		if ( $levelAbstract[$yCoord-1][$xCoord] === 2 And $levelAbstract[$yCoord-2][$xCoord] !== 0 )	$upDirection = False ;
		$downDirection = True ;
		if ( $levelAbstract[$yCoord+1][$xCoord] === 1 )	$downDirection = False ;
		if ( $levelAbstract[$yCoord+1][$xCoord] === 2 And $levelAbstract[$yCoord+2][$xCoord] !== 0 )	$downDirection = False ;
	@endphp
@endsection

@section('sidebar')
	@parent
	<div class="text-3xl text-center mt-5">
		<div class="text-4xl font-semibold inline">
		{!! __('messages.level') !!}{{ $currentLevel }}
		</div>
		@if ( $control === 'None' )
			<div class="inline">
			<form method="HEAD" action="{{ '/game/controls' }}">
				@csrf
				<button type="submit" name="Direction" value="Reload" class="mt-2 text-black bg-yellow-300 hover:bg-yellow-400 focus:ring-2 focus:ring-yellow-400 font-medium rounded-lg text-2xl px-5 py-1.5 text-center mr-2 mb-2">{!! __('messages.restart') !!}</button><br>
			    <legend class="text-xl mt-2">{{ __('messages.change') }}<br></legend>
				@if ( $previousLevel )
					<button type="submit" name="Direction" value="Prev" class="mt-2 px-5 py-1.5 bg-blue-600 text-white text-3xl rounded-lg hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">{!! __('messages.previous') !!}</button><br>
				@else
					<button disabled type="button" name="Direction" value="Prev" class="mt-2 px-5 py-1.5 cursor-not-allowed bg-gray-400 text-black text-3xl rounded-lg">{!! __('messages.previous') !!}</button><br>
				@endif
				@if ( $nextLevel )
					<button type="submit" name="Direction" value="Next" class="mt-3 px-5 py-1.5 bg-blue-600 text-white text-3xl rounded-lg hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">{!! __('messages.next') !!}</button><br>
				@else
					<button disabled type="button" name="Direction" value="Next" class="mt-3 px-5 py-1.5 cursor-not-allowed bg-gray-400 text-black text-3xl rounded-lg">{!! __('messages.next') !!}</button><br>
				@endif
			</form>
			</div>
			<div class="mt-7 flex">
				<div class="text-2xl text-left inline">
				{!! __('messages.gamemoves') !!}{{ $moves }}
				</div>
			</div>
			@if ( $winOfGame === 'False' )
				<div class="mt-7 flex inline">
				<form method="HEAD" action="{{ '/game/controls' }}">
					@csrf
				    <legend>{{ __('messages.controls') }}<br></legend>
					<div class="flex-col border-4 border-black mt-2 p-4">
						@php
							if ( $upDirection ) echo '<button type="submit" name="Direction" value="Up" class="px-5 py-1.5 bg-blue-600 text-white text-5xl leading-tight rounded-full hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800"><font face="entypo">&#x2b06</font></button><br>' ;
							else echo '<button disabled type="button" name="Direction" value="Up" class="px-5 py-1.5 cursor-not-allowed bg-gray-400 text-white text-5xl leading-tight rounded-full shadow-md"><font face="entypo">&#x2b06</font></button><br>' ;
							if ( $leftDirection ) echo '<button type="submit" name="Direction" value="Left" class="px-5 py-1.5 bg-blue-600 text-white text-5xl leading-tight rounded-full hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800"><font face="entypo">&#x2b05</font></button>&nbsp;&nbsp;&nbsp;' ;
							else echo '<button disabled type="button" name="Direction" value="Left" class="px-5 py-1.5 cursor-not-allowed bg-gray-400 text-white text-5xl leading-tight rounded-full shadow-md"><font face="entypo">&#x2b05</font></button>&nbsp;&nbsp;&nbsp;' ;
							if ( $rightDirection ) echo '<button type="submit" name="Direction" value="Right" class="px-5 py-1.5 bg-blue-600 text-white text-5xl leading-tight rounded-full hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800"><font face="entypo">&#x27a1</font></button><br>' ;
							else echo '<button disabled type="button" name="Direction" value="Right" class="px-5 py-1.5 cursor-not-allowed bg-gray-400 text-white text-5xl leading-tight rounded-full shadow-md"><font face="entypo">&#x27a1</font></button><br>' ;
							if ( $downDirection ) echo '<button type="submit" name="Direction" value="Down" class="px-5 py-1.5 bg-blue-600 text-white text-5xl leading-tight rounded-full hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800"><font face="entypo">&#x2b07</font></button><br>' ;
							else echo '<button disabled type="button" name="Direction" value="Down" class="px-5 py-1.5 cursor-not-allowed bg-gray-400 text-white text-5xl leading-tight rounded-full shadow-md"><font face="entypo">&#x2b07</font></button><br>' ;
						@endphp
					</div>
				</form>
				</div>
			@endif
		@elseif ( $control === 'ReloadQ' )
			<div class="inline">
				<div class="flex-col rounded-xl text-white bg-red-600 text-center mt-3 p-3">
					{!! __('messages.reload') !!}
				</div>
				<div class="mt-5 inline-flex items-center">
					<form method="HEAD" action="{{ '/game/controls' }}">
						@csrf
						<button type="submit" name="Direction" value="RldQNo" class="px-5 py-1.5 bg-blue-600 text-white text-3xl rounded-lg hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">{!! __('messages.reloadqno') !!}</button>
						&nbsp;&nbsp;
						<button type="submit" name="Direction" value="RldQYes" class="px-5 py-1.5 bg-yellow-400 text-black text-3xl rounded-lg hover:bg-yellow-500 focus:bg-yellow-500 active:bg-yellow-600">{!! __('messages.reloadqyes') !!}</button><br>
					</form>
				</div>
			</div>
		@elseif ( $control === 'PrevQ' )
			<div class="inline">
				<div class="flex-col rounded-xl text-white bg-green-600 text-center mt-3 p-3">
					{!! __('messages.prevq') !!}
				</div>
				<div class="mt-5 inline-flex items-center">
					<form method="HEAD" action="{{ '/game/controls' }}">
						@csrf
						<button type="submit" name="Direction" value="PrvQNo" class="px-5 py-1.5 bg-blue-600 text-white text-3xl rounded-lg hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">{!! __('messages.prvqno') !!}</button>
						&nbsp;&nbsp;
						<button type="submit" name="Direction" value="PrvQYes" class="px-5 py-1.5 bg-yellow-400 text-black text-3xl rounded-lg hover:bg-yellow-500 focus:bg-yellow-500 active:bg-yellow-600">{!! __('messages.prvqyes') !!}</button><br>
					</form>
				</div>
			</div>
		@elseif ( $control === 'NextQ' )
			<div class="inline">
				<div class="flex-col rounded-xl text-white bg-green-600 text-center mt-3 p-3">
					{!! __('messages.nextq') !!}
				</div>
				<div class="mt-5 inline-flex items-center">
					<form method="HEAD" action="{{ '/game/controls' }}">
						@csrf
						<button type="submit" name="Direction" value="NxtQNo" class="px-5 py-1.5 bg-blue-600 text-white text-3xl rounded-lg hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800">{!! __('messages.prvqno') !!}</button>
						&nbsp;&nbsp;
						<button type="submit" name="Direction" value="NxtQYes" class="px-5 py-1.5 bg-yellow-400 text-black text-3xl rounded-lg hover:bg-yellow-500 focus:bg-yellow-500 active:bg-yellow-600">{!! __('messages.prvqyes') !!}</button><br>
					</form>
				</div>
			</div>
		@endif
		@if ( $winOfGame === 'WinOfLevel' )
			<div class="flex-col rounded-xl text-white bg-green-600 text-center mt-5 p-3">
				{!! __('messages.levelwin') !!}
			</div>
		@elseif ( $winOfGame === 'WinOfGame' )
			<div class="flex-col rounded-xl text-white bg-green-600 text-center mt-5 p-3">
				{!! __('messages.gamewin') !!}
			</div>
		@endif
	</div>
@endsection

@section('footer')
	@parent
	<span class="text-xl font-bold my-2"></span>
@endsection
