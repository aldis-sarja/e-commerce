<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
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
</div>

<div class="p-6 py-4">
    {{--    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">--}}

    <div class="p-6 ml-4">
        <form method="POST" action="products">
            @csrf
            <div class="flex-col">
                <div class="flex-row">
                    <input class="p-6" type="text" placeholder="Product Name..." name="name" required>
                    <label class="p-6 ml-4" for="price">Price:</label>
                    <input class="w-20" type="number" min="0.01" step="0.01" name="price"
                                                            id="price" required>


                    <label class="p-6 ml-4" for="amount">Amount:</label>
                    <input class="w-16" type="number" min="1" step="1" value="1" name="amount"
                                                              id="amount" required>



                    <label class="p-6 ml-4" for="VAT">VAT:</label>
                    <input class="w-12" type="number" min="1" step="1" value="21" name="VAT"
                                                        id="VAT" required>%

                </div>
                <div class="flex flex-col sm:justify-end">
                <textarea class="p-4 mt-8" placeholder="Description..." name="description" required></textarea>
                <input class="rounded-full p-4 mt-4 w-32 cursor-pointer bg-stone-400" type="submit" value="Add Product">
                </div>
            </div>
        </form>
    </div>

    @if ($products)
        <ul>
            @foreach ($products as $name => $product)
                <li class="py-4">
                    <div class="flex flex-row sm:justify-between">
                        <div>
                            <a href="products/{{ $product->first()->name }}">
                            {{ $name }}
                            </a>
                            &emsp;€ {{ round($product->first()->price/100, 2) }}
                            &emsp; amount: {{ $product->count() }}
                        </div>
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
