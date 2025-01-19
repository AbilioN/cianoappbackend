<?php

namespace App\Livewire\History;

use App\Models\Aquarium;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $user;
    
    public function mount($id)
    {
        $this->user = User::find($id);
    }
    
    public function render()
    {
        $query = Aquarium::query();
        $aquariums = $query->where('user_id', $this->user->id);
        
        return view('livewire.history.show', ['aquariums' => $aquariums->paginate(10, ['*'], 'parentPage')]);
    }
}
