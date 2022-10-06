<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>E-commerce {{ $product->first()->name }}</title>

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
        Store
    </a>
</div>

<div class="p-6 py-4">

    <div class="p-6 ml-4">
        <h2 class="justify-center">{{ $product->first()->name }}</h2>
        <hr>

        <div class="flex flex-col">
            <div class="flex flex-row mt-8 mb-8">

                <form method="POST" action="/products/{{ $product->first()->name }}/change_name">
                    @csrf
                    <div class="flex flex-col">
                        <div class="flex flex-row">

                            <label class="" for="name">Name:</label>
                            <input class="ml-2" type="text" value="{{ $product->first()->name }}" name="new_name" id="name"
                                   required>
                            <input type="hidden" name="name" value="{{ $product->first()->name }}">
                        </div>
                        <input class="rounded-full p-1 w-32 mt-4 cursor-pointer bg-stone-400 hover:shadow-sm"
                               type="submit" value="Change">
                    </div>
                </form>

                <form method="POST" action="/products/{{ $product->first()->name }}/change_price">
                    @csrf
                    <div class="flex flex-col ml-4">
                        <div class="flex flex-row">
                            <label class="" for="price">Price:</label>
                            <input class="w-20 ml-2" type="number" min="0.01" step="0.01" name="price"
                                   value="{{ round($product->first()->price/100, 2) }}"
                                   id="price" required>
                            <input type="hidden" name="name" value="{{ $product->first()->name }}">
                        </div>
                        <input class="rounded-full p-1 w-32 mt-4 cursor-pointer bg-stone-400"
                               type="submit" value="Change">
                    </div>
                </form>


                <form method="POST" action="/products/{{ $product->first()->name }}/change_amount">
                    @csrf
                    <div class="flex flex-col ml-12">
                        <div class="flex flex-row">
                            <label class="" for="amount">Amount:</label>
                            <input class="w-16 ml-2" type="number" min="0" step="1" name="amount"
                                   value="{{ $product->count() }}"
                                   id="amount" required>
                            <input type="hidden" name="name" value="{{ $product->first()->name }}">
                        </div>
                        <input class="rounded-full p-1 w-32 mt-4 cursor-pointer bg-stone-400"
                               type="submit" value="Change">
                    </div>
                </form>


                <form method="POST" action="/products/{{ $product->first()->name }}/change_vat">
                    @csrf
                    <div class="flex flex-col ml-12">
                        <div class="flex flex-row">
                            <label class="" for="VAT">VAT:</label>
                            <input class="w-12 ml-2" type="number" min="1" step="1" name="VAT"
                                   value="{{ $product->first()->VAT }}"
                                   id="VAT" required>%
                            <input type="hidden" name="name" value="{{ $product->first()->name }}">
                        </div>
                        <input class="rounded-full p-1 w-32 mt-4 cursor-pointer bg-stone-400"
                               type="submit" value="Change">
                    </div>
                </form>

            </div>

            <form method="POST" action="/products/{{ $product->first()->name }}/change_description">
                @csrf
                <div class="flex flex-col mt-8">
                    <label class="" for="description">Description:</label>
                    <textarea class="p-4 mt-4" name="description" id="description"
                              required>{{ $product->first()->description }}</textarea>
                    <input type="hidden" name="name" value="{{ $product->first()->name }}">
                    <input class="rounded-full p-1 mt-4 w-32 cursor-pointer bg-stone-400" type="submit" value="Change">

                </div>
            </form>
        </div>

    </div>

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
