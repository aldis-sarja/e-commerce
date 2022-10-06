<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>E-commerce</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .bg-stone-400 {
            background-color: rgb(168 162 158);
        }
    </style>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="antialiased">

<div class="flex items-top sm:justify-between p-6">
    <a href="/">
        <h2>
            Shopping
        </h2>
    </a>
    <a href="/products"
       class="mt-8">
        Products
    </a>
</div>

<div class="p-16">
    <div class="flex flex-row">
        <h2>Congratulation! Your order has been sent. Your order code - <b>{{ $code }}</b></h2>
    </div>

</div>
</body>
</html>
