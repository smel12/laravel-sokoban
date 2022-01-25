@extends('layouts.gamelayout')

@section('title', __('xjetstream.title-verify-email'))

@section('header')
	@parent
	<div class="text-5xl font-bold text-blue-600 mt-1">Sokoban</div>
@endsection

@section('content')
	@parent
		<div class="m-auto text-3xl font-bold text-black">
			@if (session('status'))
            <div class="mb-4 font-medium text-xl text-green-600">
                {{ __('xjetstream.verify-status') }}
            </div>
        @endif
        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <x-jet-button class="ml-6 rounded-xl text-2xl text-white bg-green-400 hover:bg-green-500" type="submit">
                        {{ __('xjetstream.verify-button') }}
                    </x-jet-button>
                </div>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">		<!-- class="ml-6 rounded-xl text-2xl text-white bg-gray-600 hover:bg-gray-900" -->
                    {{ __('xjetstream.verify-logout') }}
                </button>
            </form>
        </div>
@endsection

@section('sidebar')
	@parent
	<div class="flex h-screen text-xl text-black text-left font-normal">
		<div class="flex-col rounded-xl text-white bg-blue-600 text-center p-3 m-auto">
			{{ __('xjetstream.verify-mail-hint') }}
		</div>
	</div>
@endsection

@section('footer')
	@parent
@endsection
