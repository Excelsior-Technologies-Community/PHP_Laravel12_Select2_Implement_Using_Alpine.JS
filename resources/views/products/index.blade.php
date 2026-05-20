<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 12px;
            margin-right: 4px;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
        }

        .btn-blue {
            background: #2563eb;
            color: white;
        }

        .btn-gray {
            background: #6b7280;
            color: white;
        }

        .btn-red {
            background: #dc2626;
            color: white;
        }

        .table-row:hover {
            background: #f9fafb;
        }

        .suggestion-box {
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-radius: 8px;
            background: white;
            display: none;
        }

        .suggestion-item {
            cursor: pointer;
            padding: 10px 15px;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
        }

        .suggestion-item:hover, .suggestion-item.active-item {
            background-color: #f0f4f8;
        }

        .recent-search-tag {
            cursor: pointer;
            background: #e5e7eb;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            transition: background 0.2s;
        }

        .recent-search-tag:hover {
            background: #d1d5db;
        }
    </style>
</head>

<body class="bg-gray-100 p-6">

    <div class="max-w-7xl mx-auto" x-data="productSearchApp()">

        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">📦 Products</h1>
                <p class="text-gray-500 text-sm">Manage all products</p>
            </div>

            <a href="{{ route('products.create') }}" class="btn btn-blue">
                + Add Product
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="card p-4 mb-5">
            <form method="GET" action="{{ route('products.index') }}" id="searchForm" class="flex gap-3 items-start">

                <div class="relative w-1/3">
                    <input type="text" name="search" id="search" x-model="searchQuery"
                        @input.debounce.200ms="fetchSuggestions"
                        @keydown.arrow-down.prevent="moveDown"
                        @keydown.arrow-up.prevent="moveUp"
                        @keydown.enter.prevent="selectActiveSuggestion"
                        @click.outside="closeDropdown"
                        @focus="showRecent = true"
                        placeholder="Search product..."
                        class="border px-3 py-2 rounded w-full" autocomplete="off">

                    <div class="suggestion-box border mt-1" :style="suggestions.length > 0 ? 'display: block;' : 'display: none;'">
                        <template x-for="(item, index) in suggestions" :key="item.id">
                            <div @click="selectSuggestion(item.name)"
                                 class="suggestion-item text-gray-800"
                                 :class="{ 'active-item': index === activeIndex }"
                                 x-text="item.name"></div>
                        </template>
                    </div>

                    <div class="mt-2" x-show="recentSearches.length > 0 && showRecent && searchQuery === ''" x-transition>
                        <span class="text-gray-500 text-xs me-2">Recent:</span>
                        <template x-for="search in recentSearches" :key="search">
                            <span class="recent-search-tag me-2 mb-1 text-gray-700" @click="selectSuggestion(search)" x-text="search"></span>
                        </template>
                        <span class="text-red-500 text-xs cursor-pointer ml-1" @click="clearRecent">Clear</span>
                    </div>
                </div>

                <select name="category" class="border px-3 py-2 rounded w-1/3">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-blue">Filter</button>

                <a href="{{ route('products.index') }}" class="btn btn-gray">Reset</a>

                <a href="{{ route('products.trash') }}" class="btn btn-red">Trash</a>

            </form>
        </div>

        <div class="card overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Description</th>
                        <th class="px-6 py-3 text-left">Categories</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($products as $index => $product)
                        <tr class="table-row">

                            <td class="px-6 py-4">
                                {{ $products->firstItem() + $index }}
                            </td>

                            <td class="px-6 py-4 font-semibold">
                                {{ $product->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $product->description ?? 'No description' }}
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $categoryNames = \App\Models\Category::whereIn('id', $product->categories ?? [])
                                        ->pluck('name')
                                        ->toArray();
                                @endphp

                                @foreach($categoryNames as $name)
                                    <span class="badge">{{ $name }}</span>
                                @endforeach
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('products.show', $product->id) }}" class="text-blue-600">View</a>
                                <a href="{{ route('products.edit', $product->id) }}" class="text-yellow-600 ml-2">Edit</a>

                                <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 ml-2"
                                        onclick="return confirm('Are You Sure Delete This Product?')">
                                        Delete
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">
                                No products found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>

    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
    function productSearchApp() {
        return {
            searchQuery: '{{ request('search') }}',
            suggestions: [],
            activeIndex: -1,
            recentSearches: JSON.parse(localStorage.getItem('recent_searches') || '[]'),
            showRecent: false,

            fetchSuggestions() {
                this.activeIndex = -1;
                if (this.searchQuery.trim() === '') {
                    this.suggestions = [];
                    return;
                }

                axios.get('/products/suggestions', {
                    params: { search: this.searchQuery }
                })
                .then(res => {
                    this.suggestions = res.data;
                })
                .catch(err => {
                    console.error(err);
                });
            },

            moveDown() {
                if (this.suggestions.length > 0) {
                    this.activeIndex = (this.activeIndex + 1) % this.suggestions.length;
                }
            },

            moveUp() {
                if (this.suggestions.length > 0) {
                    this.activeIndex = (this.activeIndex - 1 + this.suggestions.length) % this.suggestions.length;
                }
            },

            selectActiveSuggestion() {
                if (this.activeIndex >= 0 && this.activeIndex < this.suggestions.length) {
                    this.selectSuggestion(this.suggestions[this.activeIndex].name);
                } else {
                    this.selectSuggestion(this.searchQuery);
                }
            },

            selectSuggestion(name) {
                if (name.trim() === '') return;
                this.searchQuery = name;
                this.suggestions = [];
                
                if (!this.recentSearches.includes(name)) {
                    this.recentSearches.unshift(name);
                    this.recentSearches = this.recentSearches.slice(0, 5);
                    localStorage.setItem('recent_searches', JSON.stringify(this.recentSearches));
                }

                this.$nextTick(() => {
                    document.getElementById('searchForm').submit();
                });
            },

            clearRecent() {
                this.recentSearches = [];
                localStorage.removeItem('recent_searches');
            },

            closeDropdown() {
                setTimeout(() => {
                    this.suggestions = [];
                    this.showRecent = false;
                }, 200);
            }
        }
    }
    </script>
</body>

</html>