<?php

namespace App\Livewire\Partner;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{

    public User $user;

    public $name;
    public $email;
    public $role;
    public $password;
    public $credits = 0;

    public $isValidated = false;

    public function mount($id)
    {
        $this->user = User::find($id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->role = $this->user->role;
        $this->name = $this->user->name;

    }
    public function render()
    {
        return view('livewire.partner.edit');
    }
}
