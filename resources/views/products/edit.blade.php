<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Edit Product: {{ $product->name }}</h2>
                        <a href="{{ route('products') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Back to Products
                        </a>
                    </div>

                    <form method="POST" action="{{ route('products.update', $product->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="product_category_id" id="category" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->product_category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->translations->first()?->name ?? $category->slug }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Image URL -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input type="text" name="image" id="image" value="{{ old('image', $product->image) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Product Details -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Details</label>
                            <div id="details-container" class="space-y-4">
                                @foreach($product->details as $index => $detail)
                                    @php
                                        $detailContent = json_decode($detail->translations->first()?->content ?? '{}', true);
                                    @endphp
                                    <div class="flex gap-4 items-start">
                                        <div class="flex-1">
                                            <input type="text" name="details[{{ $index }}][type]" 
                                                value="{{ $detailContent['type'] ?? '' }}" 
                                                placeholder="Type" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" name="details[{{ $index }}][value]" 
                                                value="{{ $detailContent['value'] ?? $detailContent['content'] ?? '' }}" 
                                                placeholder="Value" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div class="flex-none">
                                            <button type="button" onclick="removeDetail(this)" 
                                                class="px-3 py-2 text-red-600 hover:text-red-800">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addDetail()" 
                                class="mt-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                Add Detail
                            </button>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addDetail() {
            const container = document.getElementById('details-container');
            const index = container.children.length;
            const detailHtml = `
                <div class="flex gap-4 items-start">
                    <div class="flex-1">
                        <input type="text" name="details[${index}][type]" 
                            placeholder="Type" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="details[${index}][value]" 
                            placeholder="Value" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-none">
                        <button type="button" onclick="removeDetail(this)" 
                            class="px-3 py-2 text-red-600 hover:text-red-800">
                            Remove
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', detailHtml);
        }

        function removeDetail(button) {
            button.closest('.flex').remove();
        }
    </script>
    @endpush
</x-app-layout> 