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

                            @foreach( $aquariums as $aquarium )
                                
                                <li onclick="toggleDiv({{$aquarium->id}})" class="p-3 hover:bg-[#e7f7f7] hover:cursor-pointer ">
                                    {{ $aquarium->name }}
                                </li>

                                @livewire('history.aquarium', ['user' => $user, 'aquariumId' => $aquarium->id], key( $aquarium->id ))
                                
                            @endforeach

                        </ul>

                        <div class="py-5">
                            {{ $aquariums->links() }}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>

    <script>
        function toggleDiv(id) {
        const proximo = document.getElementById('next-to-' + id);
          proximo.classList.toggle('max-h-[0px!important]');
        }
      </script>
    
</div>
