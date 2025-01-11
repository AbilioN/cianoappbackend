<div>
    {{-- Do your work, then step back. --}}

    <div class="mt-12 flex justify-center">
        <div class="max-w-lg w-full bg-white shadow-md rounded-md border border-solid border-gray-100">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-8">Cadastro</h2>
                    <div class="mb-6">
                        <label for="name" class="block text-gray-800 mb-2">Nome</label>
                        <input wire:model.live.debounce.500="name" type="text" id="name" name="name" class="w-full bg-gray-100 rounded-md py-2 px-4 text-gray-800 focus:outline-none focus:ring focus:border-blue-300" placeholder="Digite seu nome" required>
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-gray-800 mb-2">Email</label>
                        <input wire:model.live.debounce.500="email" type="email" id="email" name="email" class="w-full bg-gray-100 rounded-md py-2 px-4 text-gray-800 focus:outline-none focus:ring focus:border-blue-300" placeholder="Digite seu email" required>
                    </div>
                    <div class="mb-6">
                        <label for="password" class="block text-gray-800 mb-2">Senha</label>
                        <input wire:model.live.debounce.500="password" type="password" id="password" name="password" class="w-full bg-gray-100 rounded-md py-2 px-4 text-gray-800 focus:outline-none focus:ring focus:border-blue-300" placeholder="Digite sua senha" required>
                    </div>
                    <div class="mb-6">
                        <label for="role" class="block text-gray-800 mb-2">Função</label>
                        <select wire:model.live="role" id="role" name="role" class="w-full bg-gray-100 rounded-md py-2 px-4 text-gray-800 focus:outline-none focus:ring focus:border-blue-300" required>
                            <option value="">Selecione a função</option>
                            <option value="1">Admin</option>
                            <option value="2">Parceiro</option>
                        </select>
                    </div>
                    <div class="mb-6">
                    <label for="name" class="block text-gray-800 mb-2">Creditos iniciais</label>
                    <input wire:model.live.debounce.500="credits" type="text" id="name" name="name" class="w-full bg-gray-100 rounded-md py-2 px-4 text-gray-800 focus:outline-none focus:ring focus:border-blue-300" placeholder="Digite seu nome" required>
                    </div>
                    {{-- <div class="mb-6">
                        <button wire:click="execute" type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-md focus:outline-none focus:ring focus:border-blue-300">Cadastrar</button>
                    </div> --}}
                    <button 
                    wire:click="execute" 
                    type="submit" 
                    class="{{ $isValidated ? 'bg-gray-400 text-gray-500' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }} font-bold py-2 px-4 rounded-md focus:outline-none focus:ring focus:border-blue-300"
                    {{ $isValidated ? 'disabled' : '' }}>
                    {{ $isValidated ? 'Cadastrar' : 'Cadastrar' }}
                </button>
            </div>
        </div>
    </div>
</div>
