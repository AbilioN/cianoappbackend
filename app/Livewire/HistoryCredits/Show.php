<?php

namespace App\Livewire\HistoryCredits;

use App\Models\CreditHistory;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination; 

    // public $histories;

    public function mount()
    {
        // $this->histories = CreditHistory::orderByDesc('when')->paginate(10);
    }

    public function render()
    {
        // $histories = CreditHistory::orderByDesc('when')->paginate(10);

        return view('livewire.history-credits.show' , [
            // 'histories' => $histories,
        ]);
    }
}
