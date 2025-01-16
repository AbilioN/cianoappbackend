<?php

namespace App\Livewire\History;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{

    public $user;
    
    public function mount($id)
    {
        $this->user = User::find($id);
    }
    
    public function render()
    {
        return view('livewire.history.show');
    }
}
