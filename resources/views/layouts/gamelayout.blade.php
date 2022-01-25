<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title') - Sokoban</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
		@livewireStyles
	</head>
	<body class="antialiased">
		<div class="bg-bg1-texture bg-repeat">
			<div class="max-w-7xl mx-auto">
				<main class="flex flex-col h-screen">
					<div class="flow-root bg-truegray-200 pt-1 pb-3">
						<div class="flex">
							<div class="flex-1 text-left">
								<div class="flex flex-col">
									<div class="text-base pt-3 pb-3"></div>
									<div class="text-base mt-1 pl-10 flex">
										@php
											$locale = App::currentLocale() ;
											if ( $locale == 'ru' ) echo '<img src="images/RU.png">&nbsp;русский&nbsp;(RU)&nbsp;&nbsp;&nbsp;<img src="images/GB.png">&nbsp;<a href="/lang/en" class="underline">english&nbsp;(GB)</a>&nbsp;&nbsp;&nbsp;<img src="images/UA.png">&nbsp;<a href="/lang/ua" class="underline">українська&nbsp;(UA)</a>' ;
											if ( $locale == 'en' ) echo '<img src="images/GB.png">&nbsp;english&nbsp;(EN)&nbsp;&nbsp;&nbsp;<img src="images/RU.png">&nbsp;<a href="/lang/ru" class="underline">русский&nbsp;(RU)</a>&nbsp;&nbsp;&nbsp;<img src="images/UA.png">&nbsp;<a href="/lang/ua" class="underline">українська&nbsp;(UA)</a>' ;
											if ( $locale == 'uk' ) echo '<img src="images/UA.png">&nbsp;українська&nbsp;(UA)&nbsp;&nbsp;&nbsp;<img src="images/GB.png">&nbsp;<a href="/lang/en" class="underline">english&nbsp;(GB)</a>&nbsp;&nbsp;&nbsp;<img src="images/RU.png">&nbsp;<a href="/lang/ru" class="underline">русский&nbsp;(RU)</a>' ;
										@endphp
									</div>
								</div>
							</div>
							<div class="contents">
								<div class="flex-1 text-center inline">
									@section('header')
									@show
								</div>
								<div class="flex-1 text-right">
									<div class="flex flex-col">
										<div class="text-base pt-3 pb-3"></div>
										<div class="text-base mt-1 pr-10">
											@if (Route::has('login'))
												@auth
													<a href="{{ url('/dashboard') }}" class="underline">{{ __('messages.dashboard') }}</a>
													&nbsp;&nbsp;&nbsp;<a href="{{ route('logout') }}" class="underline">{{ __('messages.logout') }}</a>
												@else
													@php
														$currentUrl = url() -> current() ;
														$urlArr = parse_url( $currentUrl ) ;
														$currentUri = $urlArr['path'] ;
													@endphp
													@if ( $currentUri !== '/login' )
														<a href="{{ route('login') }}" class="underline">{{ __('messages.login') }}</a>
													@endif
													@if ( $currentUri !== '/register' )
														@if (Route::has('register'))
															&nbsp;&nbsp;&nbsp;<a href="{{ route('register') }}" class="underline">{{ __('messages.register') }}</a>
														@endif
													@endif
												@endauth
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="flex flex-1 overflow-hidden">
						<div class="flex flex-col w-4/5 p-0">
							<div class="flex flex-auto bg-blue-200 overflow-auto">
								@yield('content')
							</div>
						</div>
						<div class="flex-col bg-green-200 w-1/5 p-4 inline">
							@section('sidebar')
							@show
						</div>
					</div>
					<div class="flow-root bg-truegray-200 pt-3 pb-3">
						<div class="flex">
							<div class="flex-1 text-left"></div>
							<div class="contents">
								<div class="flex-1 text-center inline">
									@section('footer')
									@show
								</div>
								<div class="flex-1 text-right">
									<div class="flex flex-col">
										<div class="text-base pt-2"></div>
										<div class="text-base pr-10">
											<span class="text-sm">2021-10-23 By Oleg</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</main>
			</div>
		</div>
	</body>
</html>
