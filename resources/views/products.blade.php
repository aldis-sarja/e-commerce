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

                <button data-collapse-toggle="mobile-menu-3" type="button" class="md:hidden text-gray-400 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300 rounded-lg inline-flex items-center justify-center" aria-controls="mobile-menu-3" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="hidden md:flex justify-between items-center w-full md:w-auto md:order-1" id="mobile-menu-3">
                <ul class="flex-col md:flex-row flex md:space-x-8 mt-4 md:mt-0 md:text-sm md:font-medium">
                     <li>
                        <a href="/products" class="bg-blue-700 md:bg-transparent text-white block pl-3 pr-4 py-2 md:text-blue-700 md:p-0 rounded" aria-current="page">Store</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
</div>

<script src="https://unpkg.com/@themesberg/flowbite@1.1.1/dist/flowbite.bundle.js"></script>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.tailgrids.com/tailgrids-fallback.css"/>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>



<div class="p-6 py-4">

    @if ($errors)
        @foreach ($errors->all() as $error)
            <div class="text-red-700 px-7">
                {{ $error }}
            </div>
        @endforeach
    @endif


    <div class="p-6 ml-4">
        <form method="POST" action="/products">
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
        <section class="bg-white py-20 lg:py-[120px]">
            <div class="container">
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full px-4">
                        <div class="max-w-full overflow-x-auto">
                            <table class="table-auto w-full">

                                <tbody>
                                @foreach($products as $name => $product)
                                    <tr>
                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           py-5
                           px-2
                           bg-[#F3F6FF]
                           border-b border-l border-[#E8E8E8]"
                                        >
                                            <a href="/products/{{ $product->first()->name }}">
                                            {{ $name }}
                                            </a>
                                        </td>
                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           py-5
                           px-2
                           bg-white
                           border-b border-[#E8E8E8]"
                                        >
                                            € {{ number_format($product->first()->price/100, 2) }}
                                        </td>
                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           py-5
                           px-2
                           bg-[#F3F6FF]
                           border-b border-[#E8E8E8]"
                                        >
                                            [{{ $product->count() }}]
                                        </td>

                                        <td class="
                           text-center text-dark
                           font-medium
                           text-base
                           py-5
                           px-2
                           bg-white
                           border-b border-[#E8E8E8]"
                                        >
                                            <a href="/products/{{ $product->first()->name }}">
                                            {{ $product->first()->description }}
                                            </a>
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

</div>
</body>
</html>
