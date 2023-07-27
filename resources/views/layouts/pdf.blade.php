<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full w-full">
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
    </style>
</head>
<body class="font-sans text-gray-900 antialiased min-h-full">
<div class="print:fixed print:top-0 print:inset-x-0">
    <div class="max-w-screen-md mx-auto p-10">
        <header class="border-b-2 border-gray-900 pb-2">
            {{$header}}
        </header>
    </div>
</div>
<table class="w-full">
    <thead>
    <tr>
        <td>
            <div class="print:h-[114px]">&nbsp;</div>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <div>
                {{ $slot }}
            </div>
        </td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <td>
            <div class="print:h-[130px]">&nbsp;</div>
        </td>
    </tr>
    </tfoot>
</table>


<div class="print:fixed print:inset-x-0 print:bottom-0">
    <div class="max-w-screen-md mx-auto p-10">
        <footer class="flex border-t-2 border-gray-900 mt-4 justify-between pt-2">
            {{$footer}}
        </footer>
    </div>
</div>
</body>
</html>
