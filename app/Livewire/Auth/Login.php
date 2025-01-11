<?php

namespace App\Livewire\Auth;

use App\Models\Aquarium;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public function render()
    {
        $slug = 'aquario Teste';
        $slugExists = Aquarium::where('slug', $slug)->where('user_id', 2)->exists();
        dd($slugExists);
        
        return view('livewire.auth.newlogin');
    }
}
