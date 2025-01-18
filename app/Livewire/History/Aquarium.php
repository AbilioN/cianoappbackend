<?php

namespace App\Livewire\History;

use App\Models\Aquarium as ModelsAquarium;
use App\Models\AquariumNotification;
use Livewire\Component;
use Livewire\WithPagination;

class Aquarium extends Component
{
    use WithPagination;

    public $user;
    // public $aquarium;
    public $aquariumId;

    public function mount($user, $aquariumId)
    {
        $this->user = $user;
        // $this->aquarium = ModelsAquarium::find($aquariumId);
        $this->aquariumId = $aquariumId;
    }
    
    public function render()
    {
        $query = AquariumNotification::query();
        $aquariumNotifications = $query->where('aquarium_id', $this->aquariumId)
        ->orderBy('is_read', 'ASC')
        ->orderBy('read_at', 'DESC')
        ->orderBy('start_date');
        
        return view('livewire.history.aquarium', ['aquariumNotifications' => $aquariumNotifications->paginate(15, ['*'], 'childPage')]);
    }
}
