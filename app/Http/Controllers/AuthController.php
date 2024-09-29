<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            Log::info('User logged in: ' . Auth::id());
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['email' => 'Email atau password Salah.']);
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'no_hp' => 'required|string|max:15', // Validasi untuk No HP
            'no_ktp' => 'required|string|max:16|unique:users', // Validasi untuk No KTP
            'role' => 'required|in:pembeli,penjual'
        ]);
    
        // Simpan pengguna baru dengan foto profil default
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp, // Simpan No HP
            'no_ktp' => $request->no_ktp, // Simpan No KTP
            'role' => $request->role,
            'profile_picture_url' => 'storage/profile/default_profile.webp' // Ganti dengan path sesuai gambar default
        ]);
    
        Auth::login($user);
    
        return redirect()->route('dashboard');
    }
    

    public function showEditProfileForm()
    {
        $user = Auth::user();
        return view('auth.edit-profile', compact('user'));
    }

    // Metode untuk update profil, termasuk upload foto
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'no_hp' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg', // Validasi file foto
        ]);

        // Update data pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Upload foto jika ada
        if ($request->hasFile('photo')) {
            // Nama file adalah nomor KTP.jpg
            $filename = $user->no_ktp . '.' . $request->photo->getClientOriginalExtension();

            // Simpan file ke storage dengan folder 'profile'
            $path = $request->file('photo')->storeAs('profile', $filename, 'public');

            // Simpan path ke dalam database (jika kamu menggunakan kolom 'profile_picture_url')
            $user->profile_picture_url = 'storage/' . $path;
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
