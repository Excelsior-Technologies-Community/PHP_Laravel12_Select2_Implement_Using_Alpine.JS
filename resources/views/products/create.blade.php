<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    @vite(['resources/css/app.css','resources/js/app.js'])

    <!-- Select2 CSS & jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Pillbox style for selected items */
        .select2-selection__choice {
            background-color: #2563eb !important;
            color: white !important;
            border: none !important;
            padding: 0 8px !important;
            margin-right: 5px !important;
            border-radius: 12px !important;
            font-size: 0.9rem;
        }
        .select2-selection__choice__remove {
            color: white !important;
            margin-right: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 text-center">Create Product</h1>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                   required>
            @error('name') <p class="text-red-600 mt-1 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Description</label>
            <textarea name="description"
                      class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                      rows="4">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-600 mt-1 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Categories Multi-Select -->
        <div class="mb-6">
            <label class="block font-medium mb-1">Categories</label>
            <select name="categories[]" class="js-example-basic-multiple w-full" multiple="multiple">
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ (collect(old('categories'))->contains($category)) ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
            @error('categories') <p class="text-red-600 mt-1 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Buttons -->
        <div class="flex items-center">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow transition">
                Save
            </button>
            <a href="{{ route('products.index') }}" class="ml-4 text-gray-700 hover:underline">Back</a>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('.js-example-basic-multiple').select2({
        placeholder: 'Select categories',
        width: '100%',
        allowClear: true,
        closeOnSelect: true
    });
});
</script>

</body>
</html>