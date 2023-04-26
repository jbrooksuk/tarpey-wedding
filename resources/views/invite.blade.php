@extends('layouts.master')

@section('content')
<div id="app">
    <article class="w-full mx-auto px-4 md:mt-8 font-serif text-gray-800 leading-normal antialiased rounded-lg flex flex-col items-center">
        <div class="w-full lg:w-1/3">
            <img src="{{ asset('names.svg') }}" class="block w-full" alt="Monica Cavendish & Oliver Tarpey" />
        </div>

        <tabs class="w-5/6 lg:w-1/2">
            @include('partials.announcements')
            <tab name="Details">
                @if(!$family->evening)
                <p class="text-2xl mb-4 tracking-tight">You are invited to the wedding of Monica Cavendish and Oliver Tarpey.</p>

                <p>We would love nothing more than for you to attend and celebrate with us, so bring along your dancing shoes!</p>
                <p>We are getting married at 12pm at <a href="https://www.manorhousealsager.com/" class="text-teal-600 font-semibold text-underline">The Manor House, Alsager, Cheshire, ST7 2QQ</a>, so please make sure you arrive and are seated by then.</p>
                <p>We look forward to hearing from and hope you can make it! <strong class="text-teal-600 font-semibold">Please RSVP by 1st June at the latest.</strong></p>

                <p>If you have any specific dietary requirements please contact us via the email address <a href="mailto:ollyandmonica@gmail.com?subject=Tarpey%20Wedding" class="underline text-teal-600 font-semibold">ollyandmonica@gmail.com</a> or via text message to
                    <strong class="text-teal-600 font-semibold">07894447747</strong>.</p>
                @else
                <p class="text-2xl mb-4 tracking-tight">You are invited to the evening reception to celebrate the marriage of Monica Cavendish and Oliver Tarpey.</p>

                <p>The party starts at 7pm at <a href="https://www.manorhousealsager.com/" class="text-teal-600 font-semibold text-underline">The Manor House, Alsager, Cheshire, ST7 2QQ</a>. We would love nothing more than for you to attend and celebrate with us, so bring along your dancing shoes!</p>
                <p>We look forward to hearing from and hope you can make it! <strong class="text-teal-600 font-semibold">Please RSVP by 1st June at the latest.</strong></p>
                @endif
            </tab>
            <tab name="Gifts">
                <blockquote class="pl-4 py-3 border-l-2 mb-2">
                    <p class="italic">The most important gift to us is having you share in our special day.</p>
                    <p class="italic">But if you wish to contribute in some other way, we’d love a few pennies to put in our pot for our
                        honeymoon trip after tying the knot.</p>
                </blockquote>

                <p>As you all know we moved into our home six years ago and have worked hard to make our house a home.</p>
                <p>We do not ask for any gifts, your presence at our wedding is all we want.</p>
                <p>If you'd like to give us a little something extra, a contribution to our honeymoon would be awesome in making our trip even more amazing.</p>
            </tab>
            <tab name="RSVP">
                <p class="text-2xl mb-4">RSVP</p>

                <p>Please RSVP no later than the <strong class="text-teal-600 font-semibold">1st of June</strong> to let us know that you can make it.</p>

                <p>If circumstances change and you find you are unable to attend the wedding, please let us know as soon as you can. 😊</p>

                <hr class="border-t border-gray-400 my-4">

                <rsvp :family="{{ $family->toJson() }}"></rsvp>
            </tab>
        </tabs>
    </article>
</div>
@stop
