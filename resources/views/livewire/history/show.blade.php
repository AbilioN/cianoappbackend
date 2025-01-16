<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="px-12 py-8  sm:px-8"> <!-- Adicione as classes de padding aqui -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Aquários</h1>
                <p class="mt-2 text-sm text-gray-700">Lista de aquários de {{ $user->name }}</p>
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class=" min-w-auto py-2 align-middle sm:px-6 lg:px-8">

                    <div class="w-full p-4">
                        <ul>
                            <li onclick="toggleDiv(1)" class="p-3 hover:bg-[#e7f7f7] hover:cursor-pointer ">
                                Aquario 1
                            </li>
                            <div id="next-to-1" class="overflow-hidden max-h-0 max-h-[500px] transition-[max-height] duration-300 easy-in-out">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Notificação</th>
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
                                        <tr class="hover:bg-[#f7f7f7]">
                                            <td class="whitespace-nowrap py-5 text-sm sm:pl-0">
                                                <div class="font-medium text-gray-900">John Doe</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900">john.doe@email.com</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900"></div>
                                                
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">Parceiro</td>
                                            <td class="whitespace-nowrap py-5 text-sm text-green-700">
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> --}}

                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                                                
                                            </td>
                                            <td class="relative whitespace-nowrap py-5 text-sm font-medium sm:pr-0">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Historico</a>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-[#f7f7f7]">
                                            <td class="whitespace-nowrap py-5 text-sm sm:pl-0">
                                                <div class="font-medium text-gray-900">jane Doe</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900">jane.doe@email.com</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900"></div>
                                                
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">Parceiro</td>
                                            <td class="whitespace-nowrap py-5 text-sm text-green-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg> --}}
                                            </td>
                                            <td class="relative whitespace-nowrap py-5 text-sm font-medium sm:pr-0">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Historico</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <li onclick="toggleDiv(2)" class="p-3 hover:bg-[#e7f7f7] hover:cursor-pointer ">
                                Aquario 2
                            </li>
                            <div id="next-to-2" class="overflow-auto max-h-0 max-h-[500px] transition-[max-height] duration-300 easy-in-out">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Notificação</th>
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
                                        <tr class="hover:bg-[#f7f7f7]">
                                            <td class="whitespace-nowrap py-5 text-sm sm:pl-0">
                                                <div class="font-medium text-gray-900">John Doe</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900">john.doe@email.com</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900"></div>
                                                
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">Parceiro</td>
                                            <td class="whitespace-nowrap py-5 text-sm text-green-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg> --}}
                                            </td>
                                            <td class="relative whitespace-nowrap py-5 text-sm font-medium sm:pr-0">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Historico</a>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-[#f7f7f7]">
                                            <td class="whitespace-nowrap py-5 text-sm sm:pl-0">
                                                <div class="font-medium text-gray-900">jane Doe</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900">jane.doe@email.com</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900"></div>
                                                
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">Parceiro</td>
                                            <td class="whitespace-nowrap py-5 text-sm text-green-700">
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> --}}

                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                                                
                                            </td>
                                            <td class="relative whitespace-nowrap py-5 text-sm font-medium sm:pr-0">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Historico</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <li onclick="toggleDiv(3)" class="p-3 hover:bg-[#e7f7f7] hover:cursor-pointer ">
                                Aquario 3
                            </li>
                            <div id="next-to-3" class="overflow-auto max-h-0 max-h-[500px] transition-[max-height] duration-300 easy-in-out">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Notificação</th>
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
                                        <tr class="hover:bg-[#f7f7f7]">
                                            <td class="whitespace-nowrap py-5 text-sm sm:pl-0">
                                                <div class="font-medium text-gray-900">John Doe</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900">john.doe@email.com</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900"></div>
                                                
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">Parceiro</td>
                                            <td class="whitespace-nowrap py-5 text-sm text-green-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg> --}}
                                            </td>
                                            <td class="relative whitespace-nowrap py-5 text-sm font-medium sm:pr-0">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Historico</a>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-[#f7f7f7]">
                                            <td class="whitespace-nowrap py-5 text-sm sm:pl-0">
                                                <div class="font-medium text-gray-900">jane Doe</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900">jane.doe@email.com</div>
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">
                                                <div class="text-gray-900"></div>
                                                
                                            </td>
                                            <td class="whitespace-nowrap py-5 text-sm text-gray-500">Parceiro</td>
                                            <td class="whitespace-nowrap py-5 text-sm text-green-700">
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-green-600" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> --}}

                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-[15px] h-[15px] fill-red-600" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                                                
                                            </td>
                                            <td class="relative whitespace-nowrap py-5 text-sm font-medium sm:pr-0">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Historico</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            

                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>

    <script>
        function toggleDiv(id) {
        const proximo = document.getElementById('next-to-' + id);
          proximo.classList.toggle('max-h-0');
        }
      </script>
    
</div>
