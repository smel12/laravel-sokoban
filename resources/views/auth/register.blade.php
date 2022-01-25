@extends('layouts.gamelayout')

@section('title', __('xjetstream.title-register'))

@section('header')
	@parent
	<div class="text-5xl font-bold text-blue-600 mt-1">Sokoban</div>
@endsection

@section('content')
	@parent
		<div class="m-auto text-3xl font-bold text-black">
            <x-jet-validation-errors class="text-xl mb-4" />
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <x-jet-label class="m-auto text-3xl font-bold text-black" for="name" value="{{ __('xjetstream.name') }}" />
                    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                <div class="mt-4">
                    <x-jet-label class="m-auto text-3xl font-bold text-black" for="email" value="{{ __('xjetstream.email') }}" />
                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>
                <div class="mt-4">
                    <x-jet-label class="m-auto text-3xl font-bold text-black" for="password" value="{{ __('xjetstream.password') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>
                <div class="mt-4">
                    <x-jet-label class="m-auto text-3xl font-bold text-black" for="password_confirmation" value="{{ __('xjetstream.confirm1') }}" />
                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-xl text-black" href="{{ route('login') }}">
                        {{ __('xjetstream.alregistered') }}
                    </a>
					<x-jet-button class="ml-6 rounded-xl text-2xl text-white bg-green-400 hover:bg-green-500">
                        {{ __('xjetstream.register') }}
                    </x-jet-button>
                </div>
            </form>
		</div>
@endsection

@section('sidebar')
	@parent
	<div class="flex h-screen text-xl text-black text-left font-normal">
		<div class="flex-col rounded-xl text-white bg-blue-600 text-center p-3 m-auto">
			{{ __('xjetstream.register-hint') }}
		</div>
	</div>
@endsection

@section('footer')
	@parent
@endsection
