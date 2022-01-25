@extends('layouts.gamelayout')

@section('title', __('xjetstream.title-login'))

@section('header')
	@parent
	<div class="text-5xl font-bold text-blue-600 mt-1">Sokoban</div>
@endsection

@section('content')
	@parent
		<div class="m-auto text-3xl font-bold text-black">
			<x-jet-validation-errors class="text-xl mb-4" />
			@if (session('status'))
				<div class="mb-4 font-medium text-xl text-green-600">
					{{ session('status') }}
				</div>
			@endif
			<form method="POST" action="{{ route('login') }}">
				@csrf
				<div>
					<x-jet-label class="m-auto text-3xl font-bold text-black" for="email" value="{{ __('xjetstream.email') }}" />
					<x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
				</div>
				<div class="mt-4">
					<x-jet-label class="m-auto text-3xl font-bold text-black" for="password" value="{{ __('xjetstream.password') }}" />
					<x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
				</div>
				<div class="block mt-4">
					<label for="remember_me" class="flex items-center">
						<x-jet-checkbox id="remember_me" name="remember" />
						<span class="ml-2 text-2xl text-black">{{ __('xjetstream.remember-me') }}</span>
					</label>
				</div>
				<div class="flex items-center justify-end mt-4">
					@if (Route::has('password.request'))
						<a class="underline text-xl text-black" href="{{ route('password.request') }}">
							{{ __('xjetstream.forgot-password') }}
						</a>
					@endif
					<x-jet-button class="ml-6 rounded-xl text-2xl text-white bg-green-400 hover:bg-green-500">
						{{ __('xjetstream.log-in') }}
					</x-jet-button>
				</div>
			</form>
		</div>
@endsection

@section('sidebar')
	@parent
	<div class="flex h-screen text-xl text-black text-left font-normal">
		<div class="flex-col rounded-xl text-white bg-blue-600 text-center p-3 m-auto">
			{{ __('xjetstream.login-hint') }}
		</div>
	</div>
@endsection

@section('footer')
	@parent
@endsection
