<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AccountDeletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Hapus akun permanen setelah konfirmasi password, lalu logout.
     */
    public function destroy(Request $request, AccountDeletion $deletion)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Password wajib diisi untuk menghapus akun.',
            'password.current_password' => 'Password salah.',
        ]);

        $user = $request->user();

        Auth::logout();
        $deletion->delete($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus.');
    }
}
