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

<!-- This is an example component -->
<div class="max-w-2xl mx-auto mt-6">

    <nav class="border-gray-200 px-2 mb-10">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <a href="/" class="flex">
                <span class="self-center text-lg font-semibold whitespace-nowrap">Shopping</span>
            </a>
            <div class="flex md:order-2">

                <button data-collapse-toggle="mobile-menu-3" type="button"
                        class="md:hidden text-gray-400 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 rounded-lg inline-flex items-center justify-center"
                        aria-controls="mobile-menu-3" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="hidden md:flex justify-between items-center w-full md:w-auto md:order-1" id="mobile-menu-3">
                <ul class="flex-col md:flex-row flex md:space-x-8 mt-4 md:mt-0 md:text-sm md:font-medium">
                    @if ($cart)
                        <li>
                            <div class="flex-col">
                                <div>
                                    <img src="pic/cart.svg" width="32" height="32" alt="Cart">
                                    Amount: [{{ $cart->purchases->count() }}]
                                </div>
                                <div>
                                    € {{ round($cart->getTotal()/100, 2) }}
                                </div>
                            </div>
                        </li>
                    @endif
                    <li></li>
                    <li></li>
                    <li></li>
                    <li>
                        <a href="/products"
                           class="bg-blue-700 md:bg-transparent text-white block pl-3 pr-4 py-2 md:text-blue-700 md:p-0 rounded"
                           aria-current="page">Store</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</div>

<script src="https://unpkg.com/@themesberg/flowbite@1.1.1/dist/flowbite.bundle.js"></script>

@if($cart)
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

        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.tailgrids.com/tailgrids-fallback.css"/>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @if ($errors)
            <div>
                @foreach ($errors->all() as $error)
                    <div class="text-red-700 px-7">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif


        <section class="bg-white py-20 lg:py-[120px]">
            <div class="container">
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full px-4">
                        <div class="max-w-full overflow-x-auto">
                            <table class="table-auto">

                                <tbody>
                                @foreach($cart->purchases as $item)
                                    <tr>
                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           px-4
                           bg-[#F3F6FF]
                           border-b border-l border-[#E8E8E8]"
                                        >
                                            <form method="POST" action="/cart/remove">
                                                @csrf
                                                <input type="hidden" name="products" value="{{ $item->product->id }}">
                                                <input type="submit" value="-" class="
                                          bg-red-400
                                          w-4
                                          cursor-pointer
                              border border-primary
                              text-primary
                              inline-block
                              rounded-2xl
                              hover:text-white"
                                                >
                                            </form>
                                        </td>
                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           py-5
                           px-4
                           bg-[#F3F6FF]
                           border-b border-l border-[#E8E8E8]"
                                        >
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           py-5
                           px-4
                           bg-white
                           border-b border-[#E8E8E8]"
                                        >
                                            € {{ number_format($item->product->price/100, 2) }}
                                        </td>

                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           px-4
                           bg-white
                           border-b border-r border-[#E8E8E8]"
                                        >
                                            <form method="POST" action="/cart/add">
                                                @csrf
                                                <input type="hidden" name="name" value="{{ $item->product->name }}">
                                                <input type="submit" value="+" class="
                                            bg-blue-400
                                            w-4
                                            cursor-pointer
                              border border-primary
                              text-primary
                              inline-block
                              rounded-2xl
                              hover:text-white"
                                                >
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <form method="POST" action="/cart/buy">
            @csrf
            <input type="submit"
                   class="rounded-full p-1 ml-6 w-16 mt-4 cursor-pointer bg-green-400 hover:text-white"
                   value="Buy">
        </form>

    </div>
</body>
</html>
