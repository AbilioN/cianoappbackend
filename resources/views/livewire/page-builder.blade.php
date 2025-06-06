<div class="max-w-2xl mx-auto py-8">
    @foreach($details as $detail)
        @switch($detail['type'])
            @case('image')
                <div class="my-4 flex justify-center">
                    <img src="{{ $detail['url'] ?? $detail['value'] ?? '' }}" alt="" class="w-full max-w-lg rounded shadow object-cover">
                </div>
                @break
            @case('large_image')
                <div class="my-4 flex justify-center">
                    <img src="{{ $detail['url'] ?? $detail['value'] ?? '' }}" alt="" class="w-full max-w-2xl rounded shadow object-cover">
                </div>
                @break
            @case('medium_image')
                <div class="my-4 flex justify-center">
                    <img src="{{ $detail['url'] ?? $detail['value'] ?? '' }}" alt="" class="w-full max-w-md rounded shadow object-cover">
                </div>
                @break
            @case('small_image')
                <div class="my-4 flex justify-center">
                    <img src="{{ $detail['url'] ?? $detail['value'] ?? '' }}" alt="" class="w-24 h-24 rounded shadow object-cover">
                </div>
                @break
            @case('text')
                <div class="my-4 px-4">
                    <div class="prose text-lg">{!! $detail['value'] ?? '' !!}</div>
                </div>
                @break
            @case('medium_text')
                <div class="my-4 px-4">
                    <div class="prose text-base">{!! $detail['value'] ?? '' !!}</div>
                </div>
                @break
            @case('small_text')
                <div class="my-4 px-4">
                    <div class="prose text-sm">{!! $detail['value'] ?? '' !!}</div>
                </div>
                @break
            @case('divider')
                <div class="my-6">
                    <hr class="border-gray-300">
                </div>
                @break
            @case('list')
                <div class="my-4 px-4">
                    <ul class="list-disc pl-8 space-y-2">
                        @foreach(($detail['items'] ?? []) as $item)
                            <li class="prose text-base">{!! $item !!}</li>
                        @endforeach
                    </ul>
                </div>
                @break
            @case('ordered_list')
                <div class="my-4 px-4">
                    <ol class="list-decimal pl-8 space-y-2">
                        @foreach(($detail['items'] ?? []) as $item)
                            <li class="prose text-base">{!! $item !!}</li>
                        @endforeach
                    </ol>
                </div>
                @break
            @case('title')
                <div class="my-6 text-center">
                    <h2 class="text-2xl font-bold">{{ $detail['text'] ?? 'Título' }}</h2>
                </div>
                @break
            @case('title_left')
                <div class="my-6 text-left px-4">
                    <h2 class="text-2xl font-bold">{{ $detail['text'] ?? 'Título' }}</h2>
                </div>
                @break
            @case('description')
                <div class="my-6 text-center px-4">
                    <div class="prose text-lg font-semibold">{{ $detail['content'] ?? '' }}</div>
                </div>
                @break
            @case('youtube')
                <div class="my-6 flex justify-center">
                    @php
                        $url = $detail['url'] ?? '';
                        $videoId = null;
                        if (preg_match('/(?:youtu.be\/|youtube.com\/(?:embed\/|v\/|watch\?v=))([\w-]{11})/', $url, $matches)) {
                            $videoId = $matches[1];
                        }
                    @endphp
                    @if($videoId)
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <span class="text-red-500">Invalid YouTube URL</span>
                    @endif
                </div>
                @break
            @case('link_button')
                <div class="my-6 flex justify-center">
                    <a href="{{ $detail['url'] ?? '#' }}" target="_blank" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors">
                        {{ $detail['text'] ?? 'Abrir link' }}
                    </a>
                </div>
                @break
            @default
                <!-- Tipo não reconhecido -->
        @endswitch
    @endforeach
</div>
