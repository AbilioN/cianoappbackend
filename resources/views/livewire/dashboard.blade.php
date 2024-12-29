<div class="px-12 py-8 sm:px-8"> <!-- Adicione as classes de padding aqui -->
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold leading-6 text-gray-900">Histórico de Créditos</h1>
        </div>
        {{-- <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.partner.create') }}" type="button" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Adicionar Novo</a>
        </div> --}}
    </div>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="min-w-auto py-2 align-middle sm:px-6 lg:px-8">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead>
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Cliente</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipo</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Data/Hora</th>
                            {{-- <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ação</th> --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($errors as $error)
                            <tr>
                                <td class="whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $error->user()->first()->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $error->type }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $error->when }}</div>
                                </td>
                                {{-- <td class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                    <a href="{{ route('admin.partner.edit', $history->id) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <!-- Botões de navegação de página para telas menores -->
            @if ($errors->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md">
                    Anterior
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:bg-gray-50 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 transition ease-in-out duration-150">
                    Anterior
                </button>
            @endif

            @if ($errors->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:bg-gray-50 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 transition ease-in-out duration-150">
                    Próximo
                </button>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md">
                    Próximo
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <!-- Informações de paginação para telas maiores -->
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    Exibindo
                    <span class="font-medium">{{ $errors->firstItem() }}</span>
                    até
                    <span class="font-medium">{{ $errors->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $errors->total() }}</span>
                    resultados
                </p>
            </div>
            <div>
                {{ $errors->links() }}
            </div>
        </div>
    </div>
</div>
