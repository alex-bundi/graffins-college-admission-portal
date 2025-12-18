<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Graffins College | Admission Portal') }}</title>
        <meta name="description" content="Graffins College Admission Portal - Apply online for certificate, diploma, and degree programs. Start your academic journey at Graffins College today.">
        <meta name="keywords" content="Graffins College, Graffins admission, college application, online admission, higher education Kenya, diploma programs, degree programs, certificate courses, Graffins courses, student portal">
        <meta name="robots" content="index, follow">

        <meta property="og:title" content="Graffins College Admission Portal - Apply Online">
        <meta property="og:description" content="Easily apply for admission at Graffins College. Explore our certificate, diploma, and degree programs and begin your academic journey today.">
        <meta property="og:url" content="https://graffinscolab.graffinscollege.com/">
        <meta property="og:image" content="{{ url('/storage/images/logo.png') }}">

        <link rel="icon" type="image/x-icon" href="/storage/images/logo.png">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-appTheme m-0 p-0 box-border scroll-smooth ">
        @inertia
    </body>
</html>
