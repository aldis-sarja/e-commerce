<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>E-commerce</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}"  rel="stylesheet">
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
        Store
    </a>

        @if ($cart)
            <a href="cart"
                class="flex-col py-4">
                <div>
                Cart: [{{ $cart->purchases->count() }}]
                </div>
                <div>
                € {{ round($cart->getTotal()/100, 2) }}
                </div>
            </a>
        @endif


</div>

<div
    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">


    @if ($products)
        <ul>
            @foreach ($products as $name => $product)
                <li class="py-4">
                    <div class="flex flex-row sm:justify-between">
                        <div>
                        {{ $name }}
                        &emsp;€ {{ round($product->first()->price/100, 2) }}
                        &emsp; amount: {{ $product->count() }}
                        </div>

                    <form method="POST" action="cart">
                        @csrf
                            <input class="rounded-full p-4 px-3 cursor-pointer bg-stone-400" type="submit" value="Add To Cart">
                            <input type="hidden" name="products" value="{{ $product->first()->id }}">
                    </form>
                    </div>
                    <p>{{ $product->first()->description }}</p>
                </li>
            @endforeach
        </ul>
    @endif

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
