<div>

    
    <div class="flex justify-between flex-wrap bg-gray-200 w-full">
        <h1 class="text-center w-full font-bold py-5 text-2xl">Dashboard</h1>
        
    </div>
    
    <div class="flex justify-between flex-wrap bg-gray-100 w-full my-3">
        
        <div class="w-[45%] bg-white m-3 p-2 shadow-md">
            <h2 class="font-semibold text-wrap">Logins nos últimos 7 dias:</h2>
            <p class="text-center text-3xl font-thin">{{ $logins7Days }}</p>
        </div>
        <div class="w-[45%] bg-white m-3 p-2 shadow-md">
            <h2 class="font-semibold text-wrap">Logins hoje:</h2>
            <p class="text-center text-3xl font-thin">{{ $loginsToday }}</p>
        </div>

        <div class="w-[45%] bg-white m-3 p-2 shadow-md">
            <h2 class="font-semibold text-wrap">Aquários criados nos últimos 30 dias:</h2>
            <p class="text-center text-3xl font-thin">{{ $aquariums30Days }}</p>
        </div>
        <div class="w-[45%] bg-white m-3 p-2 shadow-md">
            <h2 class="font-semibold text-wrap">Total de aquários:</h2>
            <p class="text-center text-3xl font-thin">{{ $totalAquariums }}</p>
        </div>
    </div>
    
</div>