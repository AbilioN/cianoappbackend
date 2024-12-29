<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}    <div class="flex flex-col">
        <div class="h-full w-full flex justify-center items-center relative"  style="min-height: 73vh !important;">
            <img src="/bg-drop.png" alt="Background Drop" class="bg absolute" />
            <div class="w-3/4 md:w-1/2 z-10 bg-sky-50 flex flex-col items-center text-black rounded-xl shadow-xl">
                <div class="font-bold text-xl text-center px-12 pt-10 pb-5">
                    <h3>Entrar</h3>
                </div>
                <div class="form">
                    <div class="w-full max-w-xs">
                        <form class="rounded px-8 pt-6 pb-8 mb-4" >
                        @csrf
                        @error('email')
                        <p class="text-red-500 text-xs italic pb-3">Email e/ou senha inválidos.</p>
                        @enderror
                          <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                              Email
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="text" placeholder="Email">
                          </div>
                          <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                              Senha
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="**********">
                          </div>
                          <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                              Cadastrar
                            </button>
                          </div>
                        </form>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
