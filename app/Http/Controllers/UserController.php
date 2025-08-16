<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(), // Auto verify for admin created users
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Fix created_at, updated_at, dan email_verified_at jika null atau bukan Carbon instance
        $needsSave = false;
        
        // Fix created_at
        if (!$user->created_at || !($user->created_at instanceof \Carbon\Carbon)) {
            $user->created_at = $user->updated_at ?? now();
            $needsSave = true;
        }
        
        // Fix updated_at
        if (!$user->updated_at || !($user->updated_at instanceof \Carbon\Carbon)) {
            $user->updated_at = now();
            $needsSave = true;
        }
        
        // Fix email_verified_at jika berupa string atau bukan Carbon
        if ($user->email_verified_at && !($user->email_verified_at instanceof \Carbon\Carbon)) {
            try {
                $user->email_verified_at = \Carbon\Carbon::parse($user->email_verified_at);
                $needsSave = true;
            } catch (\Exception $e) {
                $user->email_verified_at = now();
                $needsSave = true;
            }
        }
        
        // Save jika ada perubahan
        if ($needsSave) {
            $user->save();
        }
        
        // Load user's transactions count
        $transaksiCount = $user->transaksis()->count();
        
        return view('users.show', compact('user', 'transaksiCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Fix created_at dan updated_at jika null sebelum menampilkan form edit
        $needsSave = false;
        
        if (!$user->created_at || !($user->created_at instanceof \Carbon\Carbon)) {
            $user->created_at = $user->updated_at ?? now();
            $needsSave = true;
        }
        
        if (!$user->updated_at || !($user->updated_at instanceof \Carbon\Carbon)) {
            $user->updated_at = now();
            $needsSave = true;
        }
        
        if ($needsSave) {
            $user->save();
        }
        
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->route('users.show', $user)
                ->with('success', 'User berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deleting currently logged in user
            if (auth()->id() === $user->id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            }

            // Check if user has transactions
            $transaksiCount = $user->transaksis()->count();
            if ($transaksiCount > 0) {
                return redirect()->back()
                    ->with('error', "User tidak dapat dihapus karena memiliki {$transaksiCount} transaksi!");
            }

            $userName = $user->name;
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' berhasil dihapus!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->back()
                ->with('success', 'Password berhasil direset!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Fix all users with null timestamps (untuk maintenance)
     */
    public function fixTimestamps()
    {
        try {
            $fixedCount = 0;
            
            // Fix users dengan created_at null
            $usersWithNullCreatedAt = User::whereNull('created_at')->get();
            foreach ($usersWithNullCreatedAt as $user) {
                $user->created_at = $user->updated_at ?? now();
                $user->save();
                $fixedCount++;
            }
            
            // Fix users dengan updated_at null
            $usersWithNullUpdatedAt = User::whereNull('updated_at')->get();
            foreach ($usersWithNullUpdatedAt as $user) {
                $user->updated_at = now();
                $user->save();
                $fixedCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbaiki {$fixedCount} data user"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}