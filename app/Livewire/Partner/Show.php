<?php

namespace App\Livewire\Partner;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination; 

    public function render()
    {
        $users = User::where('role_id', '!=', 1)->paginate(10);
        return view('livewire.partner.show', [
            'users' => $users,
        ]);
    }
}
