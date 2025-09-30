{{-- @extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized')) --}}

@extends('layouts.errorsLayout')
@section('pageTitle', '401 Error')


@section('body__content')
{{-- Body Container --}}

<div class="grid p-8 gap-10 md:grid-cols-2">

        <div class="">
            <img src="{{ url('storage/images/401_error.png') }}" alt="404 Illustration" class="md:w-3/4 md:h-full">
        </div>

        <div class="order-last flex flex-col justify-between space-y-3 place-self-center md:order-first">
            <div>
                <div>
                    <h3 class="font-palanquin font-semibold underline text-BASE text-black">
                        Error @section('code', '401')
                    </h3>
                </div>
                <h2 class="font-palanquin font-bold text-2xl text-black">
                    @section('message', __('Unauthorized'))
                </h2>
            </div>

            <div class="flex flex-col space-y-4">
                <p class="font-lemonMilk font-bold text-base">
                    there is light in too.
                </p>
                <p class="font-lemonMilk font-normal text-sm">
                    But the page is missing or you assembled the link incorrectly.
                </p>

                
            </div>

            <div>
                <a href="{{ route('login') }}" class="flex flex-row space-x-2 font-lemonMilk font-normal text-sm hover:underline hover:text-companyRed">
                    <p>
                        Go Home
                    </p>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M16.72 7.72a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 0 1 0 1.06l-3.75 3.75a.75.75 0 1 1-1.06-1.06l2.47-2.47H3a.75.75 0 0 1 0-1.5h16.19l-2.47-2.47a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>

                    </div>
                </a>
            
            </div>
        </div>
</div>


@endsection