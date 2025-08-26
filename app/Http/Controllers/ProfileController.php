<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Apply middleware for authenticated users only
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Get user statistics
        $stats = [
            'total_transactions' => $user->transaksis()->count(),
            'transactions_this_month' => $user->transaksis()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'last_login' => $user->updated_at,
            'member_since' => $user->created_at,
        ];

        return view('profile.edit', compact('user', 'stats'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Custom validation messages
        $messages = [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh user lain',
            'current_password.required_with' => 'Password saat ini wajib diisi jika ingin mengubah password',
            'password.min' => 'Password baru minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];

        // If user wants to change password, current password is required
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        // Custom validation for current password
        $validator->after(function ($validator) use ($request, $user) {
            if ($request->filled('password') && $request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    $validator->errors()->add('current_password', 'Password saat ini tidak benar.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Check if email is being changed
            $emailChanged = $user->email !== $request->email;
            if ($emailChanged) {
                $updateData['email_verified_at'] = null; // Reset email verification
            }

            // Update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Log profile update
            \Log::info('User profile updated', [
                'user_id' => $user->id,
                'email_changed' => $emailChanged,
                'password_changed' => $request->filled('password'),
                'updated_at' => now(),
            ]);

            $message = 'Profile berhasil diperbarui!';
            if ($emailChanged) {
                $message .= ' Silakan verifikasi email baru Anda.';
            }

            return redirect()->route('profile.edit')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validation for account deletion
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'confirmation' => 'required|string|in:DELETE',
        ], [
            'password.required' => 'Password wajib diisi untuk menghapus akun',
            'confirmation.required' => 'Konfirmasi penghapusan wajib diisi',
            'confirmation.in' => 'Ketik "DELETE" untuk konfirmasi penghapusan akun',
        ]);

        // Check current password
        $validator->after(function ($validator) use ($request, $user) {
            if (!Hash::check($request->password, $user->password)) {
                $validator->errors()->add('password', 'Password tidak benar.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            // Check if user has transactions
            $transaksiCount = $user->transaksis()->count();
            if ($transaksiCount > 0) {
                return redirect()->back()
                    ->with('error', "Akun tidak dapat dihapus karena memiliki {$transaksiCount} transaksi. Hubungi administrator untuk bantuan.");
            }

            // Prevent admin from deleting their own account if they're the last admin
            if ($user->isAdmin()) {
                $adminCount = \App\Models\User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat menghapus akun admin terakhir di sistem.');
                }
            }

            // Log account deletion before deleting
            \Log::info('User account deleted', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'deleted_at' => now(),
            ]);

            // Logout and delete account
            Auth::logout();
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Akun Anda telah berhasil dihapus. Terima kasih telah menggunakan layanan kami.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus akun: ' . $e->getMessage());
        }
    }

    /**
     * Update user avatar/profile picture
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ], [
            'avatar.required' => 'Pilih file gambar untuk avatar',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar yang didukung: JPEG, PNG, JPG, GIF',
            'avatar.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $user = $request->user();
            
            // Store the uploaded file
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Delete old avatar if exists
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            // Update user avatar path
            $user->update(['avatar' => $avatarPath]);

            // Log avatar update
            \Log::info('User avatar updated', [
                'user_id' => $user->id,
                'avatar_path' => $avatarPath,
                'updated_at' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Avatar berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload avatar: ' . $e->getMessage());
        }
    }

    /**
     * Remove user avatar
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();
            
            // Delete avatar file if exists
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            // Clear avatar path from database
            $user->update(['avatar' => null]);

            // Log avatar removal
            \Log::info('User avatar removed', [
                'user_id' => $user->id,
                'removed_at' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Avatar berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus avatar: ' . $e->getMessage());
        }
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'nullable|string|in:light,dark,auto',
            'language' => 'nullable|string|in:id,en',
            'notifications_email' => 'boolean',
            'notifications_sms' => 'boolean',
            'timezone' => 'nullable|string|max:50',
        ], [
            'theme.in' => 'Tema harus berupa: light, dark, atau auto',
            'language.in' => 'Bahasa harus berupa: id (Indonesia) atau en (English)',
            'timezone.max' => 'Timezone maksimal 50 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $user = $request->user();
            
            // Prepare preferences data
            $preferences = [
                'theme' => $request->theme ?? 'light',
                'language' => $request->language ?? 'id',
                'notifications' => [
                    'email' => $request->boolean('notifications_email'),
                    'sms' => $request->boolean('notifications_sms'),
                ],
                'timezone' => $request->timezone ?? 'Asia/Jakarta',
            ];
            
            // Update user preferences (assuming you have a preferences JSON column)
            $user->update(['preferences' => json_encode($preferences)]);

            // Log preferences update
            \Log::info('User preferences updated', [
                'user_id' => $user->id,
                'preferences' => $preferences,
                'updated_at' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Preferensi berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui preferensi: ' . $e->getMessage());
        }
    }

    /**
     * Get user activity log
     */
    public function activityLog(Request $request)
    {
        $user = $request->user();
        
        // Get user's recent transactions as activity log
        $activities = $user->transaksis()
            ->with('barang')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $activities->map(function($transaksi) {
                return [
                    'id' => $transaksi->id,
                    'type' => 'transaction',
                    'action' => $transaksi->jenis_transaksi,
                    'description' => "Transaksi {$transaksi->jenis_transaksi} - {$transaksi->barang->nama_barang}",
                    'details' => [
                        'kode_transaksi' => $transaksi->kode_transaksi,
                        'jumlah' => $transaksi->jumlah,
                        'total_harga' => $transaksi->total_harga,
                        'barang' => $transaksi->barang->nama_barang,
                    ],
                    'created_at' => $transaksi->created_at->diffForHumans(),
                    'formatted_date' => $transaksi->created_at->format('d M Y, H:i'),
                ];
            }),
            'pagination' => [
                'current_page' => $activities->currentPage(),
                'last_page' => $activities->lastPage(),
                'total' => $activities->total(),
            ]
        ]);
    }
}