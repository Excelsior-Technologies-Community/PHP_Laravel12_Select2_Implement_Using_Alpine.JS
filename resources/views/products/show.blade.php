<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Product Details</h1>

    <p><strong>Name:</strong> {{ $product->name }}</p>
    <p class="mt-2"><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>
    <p class="mt-2"><strong>Categories:</strong> {{ implode(', ', $product->categories) }}</p>

    <a href="{{ route('products.index') }}" class="inline-block mt-4 border px-4 py-2 rounded">Back</a>
</div>

</body>
</html>