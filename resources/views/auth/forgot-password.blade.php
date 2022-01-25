@extends('layouts.gamelayout')

@section('title', __('xjetstream.title-recovery-pass'))

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
			<form method="POST" action="{{ route('password.email') }}">
				@csrf
				<div class="block">
					<x-jet-label class="m-auto text-3xl font-bold text-black" for="email" value="{{ __('xjetstream.email') }}" />
					<x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
				</div>
				<div class="flex items-center justify-end mt-4">
					<x-jet-button class="rounded-xl text-2xl text-white bg-green-400 hover:bg-green-500">
						{{ __('xjetstream.reset-link') }}
					</x-jet-button>
                </div>
            </form>
		</div>
@endsection

@section('sidebar')
	@parent
	<div class="flex h-screen text-xl text-black text-left font-normal">			<!-- Так добиваемся вертикального позиционирования контейнера в блоке посредине, но только одного,	-->
		<div class="flex-col rounded-xl bg-yellow-300 text-center p-3 m-auto">		<!-- так как они будут идти по горизонтали... Далее выводим один бабл с текущим хинтом в Сайдбар	-->
			{{ __('xjetstream.forgot-password-hint') }}
		</div>
	</div>
@endsection

@section('footer')
	@parent
@endsection
