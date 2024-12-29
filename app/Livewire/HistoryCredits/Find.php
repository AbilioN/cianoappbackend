<?php

namespace App\Livewire\HistoryCredits;

use App\Models\CreditHistory;
use App\Models\User;
use Livewire\Component;

class Find extends Component
{

    public User  $user;
    
    public function mount($id)
    {
        $this->user = User::find($id);
    }
    public function render()
    {
        
        // $historeis = CreditHistory::where('user_id' , $this->user->id)->orderByDesc('when')->paginate(10);

        return view('livewire.history-credits.find', [
            // 'histories' => $historeis,
        ]);
    }
}
