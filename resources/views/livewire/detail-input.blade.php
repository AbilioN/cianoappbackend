<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $detail['type'])) }}</span>
        </div>
        <button wire:click="$dispatch('detail-removed', { index: {{ $index }} })" class="text-red-500 hover:text-red-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>

    @switch($detail['type'])
        @case('text')
        @case('large_text')
        @case('medium_text')
        @case('small_text')
            <div>
                <textarea 
                    wire:model="value" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    rows="3"
                ></textarea>
            </div>
            @break

        @case('list')
        @case('ordered_list')
            <div class="space-y-2">
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        wire:model="newItem" 
                        placeholder="Add new item" 
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    <button 
                        wire:click="addListItem" 
                        class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                    >
                        Add
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($items as $itemIndex => $item)
                        <div class="flex items-center gap-2">
                            <input 
                                type="text" 
                                wire:model="items.{{ $itemIndex }}" 
                                wire:change="updateListItem({{ $itemIndex }})"
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                            <button 
                                wire:click="removeListItem({{ $itemIndex }})" 
                                class="text-red-500 hover:text-red-700"
                            >
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        @case('title')
        @case('title_left')
            <div>
                <input 
                    type="text" 
                    wire:model="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
            </div>
            @break
    @endswitch
</div> 