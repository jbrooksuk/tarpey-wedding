@extends('layouts.master')

@section('content')
<div class="flex items-center flex-col justify-center content-center h-screen">
    <div class="w-1/3">
        <img src="{{ asset('names.svg') }}" class="w-full" alt="Monica Canvendish & Oliver Tarpey" />
    </div>
    <div class="content-center w-half">
        <form class="w-full" method="POST" action="{{ route('post:invite') }}">
            {{ csrf_field() }}
            <div class="flex items-center py-2">
                <input class="appearance-none bg-white w-full text-gray-700 text-xl mr-3 py-2 px-2 leading-tight focus:outline-none rounded" type="text" name="invite" placeholder="Your Invite Code" aria-label="Your Invite Code" autofocus>
                <button class="flex-no-shrink bg-teal-500 hover:bg-teal-600 text-xl text-white py-2 px-2 rounded" type="submit">
                    Enter
                </button>
            </div>
        </form>
    </div>

    @if($errors->any())
    <div class="mt-2">
        <h2 class="text-xl text-red-800">{{ $errors->first() }}</h2>
    </div>
    @endif
</div>
@stop
