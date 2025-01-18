<div>
    {{-- The Master doesn't talk, he acts. --}}
    
    @if(!$aquariumNotifications)

        <h1 class="text-base font-semibold leading-6 text-gray-900">Nenhum aquário encontrado.</h1>
        
    @else
    <div wire:ignore.self id="next-to-{{ $aquariumId }}" class="overflow-hidden max-h-[0px!important] max-h-[500px] transition-[max-height] duration-300 easy-in-out">
        <table class="min-w-full divide-y divide-gray-300">
            <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">Notificação</th>
                    <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900">Início</th>
                    <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900">Fim</th>
                    <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900">Data de renovação</th>
                    <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900">Lido</th>
                    <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900">Lido em:</th>
                    <th scope="col" class="py-3.5 text-left text-sm font-semibold text-gray-900">Ativo</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                        <span class="sr-only">Edit</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($aquariumNotifications as $aquariumNotification)
                    <tr class="hover:bg-[#f7f7f7]">
                        <td class="whitespace-nowrap py-5 text-sm sm:pl-0">
                            <div class="font-medium text-gray-900">{{ $aquariumNotification->notification->name }}</div>
                        </td>
                        <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                            <div class="text-gray-900">{{ $aquariumNotification->start_date }}</div>
                        </td>
                        <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                            <div class="text-gray-900">{{ $aquariumNotification->end_date }}</div>
                            
                        </td>
                        <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                            <div class="text-gray-900">{{ $aquariumNotification->renew_date }}</div>
                        </td>
                        <td class="whitespace-nowrap py-5 text-sm text-green-700">
                            @if($aquariumNotification->is_read)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            @endif
                        </td>
                        <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                            <div class="text-gray-900">{{ $aquariumNotification->read_at }}</div>
                        </td>
                        <td class="relative whitespace-nowrap py-5 text-sm font-medium sm:pr-0">
                            @if($aquariumNotification->is_active)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $aquariumNotifications->links() }}
        </div>
    </div>
    @endif

</div>
