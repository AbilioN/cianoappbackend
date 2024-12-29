<?php 

namespace App\Livewire;

use App\Models\ErrorLog;
use Livewire\Component;

class Dashboard extends Component
{

    public function mount()
    {
    }

    public function render()
    {

        $erros = [];
        // $erros = ErrorLog::orderByDesc('when')->paginate(10);
        return view('livewire.dashboard' , [
            'errors' => $erros
        ]);
    }
}
