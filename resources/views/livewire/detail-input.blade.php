<div class="bg-white rounded-lg shadow-sm p-4 space-y-4">
    <div class="flex justify-between items-center">
        <h4 class="text-lg font-medium text-gray-900">Detail #{{ $index + 1 }}</h4>
        <div class="flex gap-2">
            @if($isDraft)
                <button 
                    wire:click="publishDraft" 
                    type="button" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm"
                >
                    Publish
                </button>
            @else
                <button 
                    wire:click="saveAsDraft" 
                    type="button" 
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm"
                >
                    Save as Draft
                </button>
            @endif
            <button 
                wire:click="removeDetail" 
                type="button" 
                class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors text-sm"
            >
                Remove
            </button>
        </div>
    </div>

    @if($isDraft)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        This detail is in draft mode. Changes are not saved until you publish.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex gap-4 items-start p-4 bg-gray-50 rounded-lg">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select 
                wire:model.live="type" 
                wire:change="changeType($event.target.value)"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Select type</option>
                <option value="text">Text</option>
                <option value="medium_text">Medium Text</option>
                <option value="small_text">Small Text</option>
                <option value="image">Image</option>
                <option value="large_image">Large Image</option>
                <option value="medium_image">Medium Image</option>
                <option value="small_image">Small Image</option>
                <option value="divider">Divider</option>
                <option value="list">List</option>
                <option value="ordered_list">Ordered List</option>
                <option value="title">Title</option>
                <option value="title_left">Title Left</option>
                <option value="description">Description</option>
                <option value="youtube">YouTube</option>
                <option value="notification_button">Notification Button</option>
                <option value="yes_or_no">Yes or No</option>
                <option value="link_button">Link Button</option>
            </select>
            @error("type") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex-1">
            @switch($type)
                @case('image')
                @case('large_image')
                @case('medium_image')
                @case('small_image')
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input 
                        type="text" 
                        wire:model="value" 
                        placeholder="https://..." 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    @error("value") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @break

                @case('text')
                @case('medium_text')
                @case('small_text')
                    <label class="block text-sm font-medium text-gray-700 mb-1">Text Content</label>
                    <textarea 
                        wire:model="value" 
                        rows="3" 
                        placeholder="Enter text content" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    ></textarea>
                    @error("value") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @break

                @case('list')
                @case('ordered_list')
                    <label class="block text-sm font-medium text-gray-700 mb-1">List Items</label>
                    <div class="space-y-2">
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                wire:model="newItem" 
                                placeholder="Add new item" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                            <button 
                                type="button" 
                                wire:click="addListItem"
                                class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                            >
                                Add
                            </button>
                        </div>
                        <div class="space-y-1">
                            @foreach($items as $itemIndex => $item)
                                <div class="flex gap-2 items-center">
                                    <input 
                                        type="text" 
                                        wire:model="items.{{ $itemIndex }}" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                    <button 
                                        type="button" 
                                        wire:click="removeListItem({{ $itemIndex }})"
                                        class="p-1 text-red-600 hover:text-red-800"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error("items") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @break

                @case('title')
                @case('title_left')
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title Text</label>
                    <input 
                        type="text" 
                        wire:model="text" 
                        placeholder="Enter title" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    @error("text") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @break

                @case('description')
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea 
                        wire:model="content" 
                        rows="4" 
                        placeholder="Enter description" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    ></textarea>
                    @error("content") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @break

                @case('youtube')
                    <label class="block text-sm font-medium text-gray-700 mb-1">YouTube Video URL</label>
                    <input 
                        type="text" 
                        wire:model="value" 
                        placeholder="https://youtube.com/..." 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                    @error("value") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @break

                @case('notification_button')
                @case('link_button')
                    <div class="space-y-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                            <input 
                                type="text" 
                                wire:model="text" 
                                placeholder="Enter button text" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                            @error("text") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                            <input 
                                type="text" 
                                wire:model="url" 
                                placeholder="https://..." 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                            @error("url") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @break

                @case('yes_or_no')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Question Title</label>
                            <input 
                                type="text" 
                                wire:model="title" 
                                placeholder="Enter the question" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            >
                            @error("title") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Yes Option</label>
                                <div class="space-y-2">
                                    <div class="flex gap-2">
                                        <textarea 
                                            wire:model="newOptionYes" 
                                            rows="3" 
                                            placeholder="Add new option for Yes" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        ></textarea>
                                        <button 
                                            type="button" 
                                            wire:click="addOptionYes"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                                        >
                                            Add
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($optionYes as $index => $option)
                                            <div class="flex gap-2 items-start">
                                                <textarea 
                                                    wire:model="optionYes.{{ $index }}.value" 
                                                    rows="3" 
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                ></textarea>
                                                <button 
                                                    type="button" 
                                                    wire:click="removeOptionYes({{ $index }})"
                                                    class="p-1 text-red-600 hover:text-red-800"
                                                >
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No Option</label>
                                <div class="space-y-2">
                                    <div class="flex gap-2">
                                        <textarea 
                                            wire:model="newOptionNo" 
                                            rows="3" 
                                            placeholder="Add new option for No" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        ></textarea>
                                        <button 
                                            type="button" 
                                            wire:click="addOptionNo"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200"
                                        >
                                            Add
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        @foreach($optionNo as $index => $option)
                                            <div class="flex gap-2 items-start">
                                                <textarea 
                                                    wire:model="optionNo.{{ $index }}.value" 
                                                    rows="3" 
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                ></textarea>
                                                <button 
                                                    type="button" 
                                                    wire:click="removeOptionNo({{ $index }})"
                                                    class="p-1 text-red-600 hover:text-red-800"
                                                >
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @break

                @case('divider')
                    <div class="text-sm text-gray-500 italic">No input needed for divider</div>
                    @break

                @default
                    @if($type)
                        <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                        <input 
                            type="text" 
                            wire:model="value" 
                            placeholder="Enter value" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error("value") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    @endif
            @endswitch
        </div>
    </div>
</div> 