<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.header');
    }
}
