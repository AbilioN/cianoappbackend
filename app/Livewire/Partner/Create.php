<?php

namespace App\Livewire\Partner;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $role;
    public $password;
    public $credits = 0;

    public $isDisabled = true;
    public $isValidated = true;
    
    public $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users.email',
        'role' => 'required',
        'password' => 'required|string|min:8',
    ];


    public $validated = [];

    public function boot()
    {

        
    }

    public function updated($propertyName , $propertyValue)
    {
        if(!isset($this->validated[$propertyName]))
        {
            $this->validated[$propertyName] = $propertyValue;
        }

        if(count($this->validated) == count($this->rules))
        {
            $this->isDisabled = false;
            $this->isValidated = false;
        }

        // $this->validateOnly($propertyName);
        // $this->isDisabled = $this->hasErrors();
    }
    public function execute()
    {

        try{

            $newData = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role,
                'credits' => $this->credits
            ];
    
            $newUser = User::create($newData);
            
            redirect(route('admin.partner.show'));

        }catch(Exception $e)
        {

        }

    }
    public function render()
    {
        return view('livewire.partner.create');
    }
}
