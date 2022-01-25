@extends('layouts.gamelayout')

@section('title', __('xjetstream.title-confirm-password'))

@section('header')
	@parent
	<div class="text-5xl font-bold text-blue-600 mt-1">Sokoban</div>
@endsection

@section('content')
	@parent
		<div class="m-auto text-3xl font-bold text-black">
			<x-jet-validation-errors class="text-xl mb-4" />
			<form method="POST" action="{{ route('password.confirm') }}">
				@csrf
				<div>
					<x-jet-label class="m-auto text-3xl font-bold text-black" for="password" value="{{ __('xjetstream.password') }}" />
					<x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
				</div>
				<div class="flex justify-end mt-4">
					<x-jet-button class="ml-6 rounded-xl text-2xl text-white bg-green-400 hover:bg-green-500">
						{{ __('xjetstream.confirm-password-button') }}
					</x-jet-button>
				</div>
			</form>
		</div>
@endsection

@section('sidebar')
	@parent
	<div class="flex h-screen text-xl text-black text-left font-normal">
		<div class="flex-col rounded-xl text-white bg-red-600 text-center p-3 m-auto">
			{{ __('xjetstream.confirm-password-hint') }}
		</div>
	</div>
@endsection

@section('footer')
	@parent
@endsection
