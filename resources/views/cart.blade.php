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

    <div class="flex-col py-4">
        <div>
            Cart: [{{ $cart->purchases->count() }}]
        </div>
        <div>
            € {{ round($cart->getTotal()/100, 2) }}
        </div>
    </div>

</div>

<div class="flex flex-col px-4">
    {{--    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">--}}

    <div class="flex flex-row">
        <div class="p-8">
            Total sum: € {{ number_format($cart->getTotal()/100, 2) }}
        </div>
        <div class="p-8">
            Subtotal sum: € {{ number_format($cart->getSubTotal()/100, 2) }}
        </div>
        <div class="p-8">
            VAT sum: € {{ number_format($cart->getVatSum()/100, 2) }}
        </div>
    </div>

    <div class="flex flex-col">
    @foreach ($cart->purchases as $item)
            <div class="flex flex-row">
            <div class="p-4">
                {{ $item->product->name }}
            </div>
            <div class="p-4">
                € {{ number_format($item->product->price/100, 2) }}
            </div>
            </div>
    @endforeach
    </div>

    <form method="POST" action="/cart/buy">
        @csrf
        <input type="submit"
               class="rounded-full p-1 w-16 mt-4 cursor-pointer bg-green-400 hover:shadow-sm"
               value="Buy">
    </form>

    @if ($errors)
        @foreach ($errors->all() as $error)
            <div class="text-red-700 px-7">
                {{ $error }}
            </div>
        @endforeach
    @endif


</div>
</body>
</html>
