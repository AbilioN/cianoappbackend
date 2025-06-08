<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
    <div class="flex justify-between items-center mb-4">
        <span class="text-sm font-medium text-gray-700">
            {{ ucfirst(str_replace('_', ' ', $component['type'])) }}
        </span>
        @if($editing)
            <button 
                wire:click="$dispatch('remove-component', { pageIndex: {{ $pageIndex }}, componentIndex: {{ $componentIndex }} })"
                class="text-red-600 hover:text-red-800"
            >
                Remove
            </button>
        @endif
    </div>

    @switch($component['type'])
        @case('text')
        @case('large_text')
        @case('medium_text')
        @case('small_text')
            <div class="space-y-2">
                <textarea
                    wire:model="value"
                    class="w-full form-input rounded-md"
                    rows="3"
                    placeholder="Enter text content..."
                    {{ !$editing ? 'disabled' : '' }}
                ></textarea>
            </div>
            @break

        @case('title')
        @case('title_left')
            <div class="space-y-2">
                <input
                    type="text"
                    wire:model="text"
                    class="w-full form-input rounded-md"
                    placeholder="Enter title..."
                    {{ !$editing ? 'disabled' : '' }}
                >
            </div>
            @break

        @case('list')
        @case('ordered_list')
            <div class="space-y-4">
                @if($editing)
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="newItem"
                            class="flex-1 form-input rounded-md"
                            placeholder="Add new item..."
                        >
                        <button
                            wire:click="addListItem"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                        >
                            Add
                        </button>
                    </div>
                @endif

                <div class="space-y-2">
                    @foreach($items as $index => $item)
                        <div class="flex items-center gap-2">
                            @if($component['type'] === 'ordered_list')
                                <span class="text-gray-500">{{ $index + 1 }}.</span>
                            @else
                                <span class="text-gray-500">•</span>
                            @endif
                            <input
                                type="text"
                                wire:model="items.{{ $index }}"
                                class="flex-1 form-input rounded-md"
                                placeholder="Item {{ $index + 1 }}"
                                {{ !$editing ? 'disabled' : '' }}
                            >
                            @if($editing)
                                <button
                                    wire:click="removeListItem({{ $index }})"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    Remove
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        @default
            <div class="text-gray-500 italic">
                Unsupported component type: {{ $component['type'] }}
            </div>
    @endswitch
</div> 