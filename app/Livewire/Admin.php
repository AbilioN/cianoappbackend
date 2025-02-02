<?php

namespace App\Livewire;

use App\Models\Aquarium;
use App\Models\LoginCounter;
use Carbon\Carbon;
use Livewire\Component;

class Admin extends Component
{
    public $logins7Days = 0;
    public $loginsToday = 0;
    public $aquariums30Days = 0;
    public $totalAquariums = 0;

    public function mount() {
        $this->logins7Days = LoginCounter::where('datetime', '>=', Carbon::today('Europe/Lisbon')->subDays(7))->get()->count();
        $this->loginsToday = LoginCounter::where('datetime', '>=', Carbon::today('Europe/Lisbon'))->get()->count();
        $this->totalAquariums = Aquarium::all()->count();
        $this->aquariums30Days = Aquarium::where('created_at', '>=', Carbon::today('Europe/Lisbon')->subDays(30))->get()->count();

    }
    
    public function render()
    {
        return view('livewire.admin');
    }
}
