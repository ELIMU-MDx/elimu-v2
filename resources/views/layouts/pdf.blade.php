<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Styles -->
    @vite('resources/css/app.css')

    <style>
        * {
            -webkit-print-color-adjust: exact
        }
        .page-number::before{
            counter-increment: page;
            content: "Page " counter(page) " of " counter(pages);
        }
    </style>
</head>
<body>
<div class="font-sans text-gray-900 antialiased">
    {{ $slot }}
</div>
</body>
</html>
