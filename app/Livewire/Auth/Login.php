<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.guest')] // Menggunakan layout guest bawaan Breeze
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $isAdmin = false; // Menangkap nilai checkbox admin

    protected $rules = [
        'email' => 'required|email', // Di UI tulisannya username, tapi kita pakai field email untuk standar bawaan
        'password' => 'required',
    ];

    public function authenticate()
    {
        $this->validate();

        // Proses autentikasi
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            // Logika khusus jika checkbox admin dicentang
            // Misalnya: menyimpan sesi khusus atau mengecek role di database
            if ($this->isAdmin) {
                session(['role' => 'admin']);
            } else {
                session(['role' => 'cashier']);
            }

            return redirect()->intended(route('dashboard'));
        }

        // Jika gagal login
        throw ValidationException::withMessages([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}