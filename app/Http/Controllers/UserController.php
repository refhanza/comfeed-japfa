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
     * Apply middleware for role-based access
     */
    public function __construct()
    {
        // Only admin and manager can access user management
        $this->middleware('role:admin,manager');
        
        // Only admin can delete users and change roles
        $this->middleware('role:admin')->only(['destroy', 'updateRole']);
    }

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
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter by verification status
        if ($request->has('verified') && $request->verified != '') {
            if ($request->verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Role-based filtering
        $currentUser = auth()->user();
        if ($currentUser->isManager()) {
            // Managers can only see staff and regular users
            $query->whereIn('role', [User::ROLE_STAFF, User::ROLE_USER]);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $stats = [
            'total' => User::count(),
            'admins' => User::admins()->count(),
            'managers' => User::role('manager')->count(),
            'staff' => User::role('staff')->count(),
            'users' => User::role('user')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
        ];
        
        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = User::getAllRoles();
        $rolePermissions = User::getRolePermissions();
        
        // Managers cannot create admin accounts
        if (auth()->user()->isManager()) {
            unset($roles[User::ROLE_ADMIN]);
        }
        
        return view('users.create', compact('roles', 'rolePermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', array_keys(User::getAllRoles())),
        ];

        // Managers cannot create admin accounts
        if (auth()->user()->isManager()) {
            $rules['role'] = 'required|in:manager,staff,user';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now(), // Auto verify for admin created users
            ]);

            // Log user creation
            \Log::info('New user created', [
                'created_by' => auth()->id(),
                'new_user_id' => $user->id,
                'new_user_role' => $user->role,
                'new_user_email' => $user->email,
            ]);

            return redirect()->route('users.index')
                ->with('success', "User {$user->name} dengan role {$user->role_name} berhasil ditambahkan!");

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
        // Managers cannot view admin details
        if (auth()->user()->isManager() && $user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat melihat detail admin.');
        }

        // Fix timestamps if needed
        $this->fixUserTimestamps($user);
        
        // Load user's transactions count
        $transaksiCount = $user->transaksis()->count();
        
        // Get recent activities (transactions)
        $recentActivities = $user->transaksis()
            ->with('barang')
            ->latest()
            ->limit(5)
            ->get();
        
        return view('users.show', compact('user', 'transaksiCount', 'recentActivities'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Managers cannot edit admin accounts
        if (auth()->user()->isManager() && $user->isAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat mengedit admin.');
        }

        // Users cannot edit accounts with higher or equal privileges
        $currentUser = auth()->user();
        if (!$this->canManageUser($currentUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat mengedit user ini.');
        }

        $this->fixUserTimestamps($user);
        
        $roles = User::getAllRoles();
        $rolePermissions = User::getRolePermissions();
        
        // Filter available roles based on current user's role
        if ($currentUser->isManager()) {
            unset($roles[User::ROLE_ADMIN]);
        }
        
        return view('users.edit', compact('user', 'roles', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Permission checks
        $currentUser = auth()->user();
        if (!$this->canManageUser($currentUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat mengedit user ini.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Only admin can change roles
        if ($currentUser->isAdmin()) {
            $rules['role'] = 'required|in:' . implode(',', array_keys(User::getAllRoles()));
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
        ]);

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

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Only admin can update role
            if ($currentUser->isAdmin() && $request->has('role')) {
                $oldRole = $user->role;
                $updateData['role'] = $request->role;
                
                // Log role change
                if ($oldRole !== $request->role) {
                    \Log::info('User role changed', [
                        'changed_by' => $currentUser->id,
                        'user_id' => $user->id,
                        'old_role' => $oldRole,
                        'new_role' => $request->role,
                    ]);
                }
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

            // Only admin can delete users
            if (!auth()->user()->isAdmin()) {
                return redirect()->back()
                    ->with('error', 'Hanya admin yang dapat menghapus user!');
            }

            // Prevent deleting admin if it's the last admin
            if ($user->isAdmin()) {
                $adminCount = User::admins()->count();
                if ($adminCount <= 1) {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat menghapus admin terakhir!');
                }
            }

            // Check if user has transactions
            $transaksiCount = $user->transaksis()->count();
            if ($transaksiCount > 0) {
                return redirect()->back()
                    ->with('error', "User tidak dapat dihapus karena memiliki {$transaksiCount} transaksi!");
            }

            $userName = $user->name;
            $userRole = $user->role;
            
            // Log deletion
            \Log::info('User deleted', [
                'deleted_by' => auth()->id(),
                'deleted_user_id' => $user->id,
                'deleted_user_name' => $userName,
                'deleted_user_role' => $userRole,
            ]);

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' ({$userRole}) berhasil dihapus!");

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
        // Permission check
        if (!$this->canManageUser(auth()->user(), $user)) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mereset password user ini.');
        }

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

            // Log password reset
            \Log::info('Password reset by admin', [
                'reset_by' => auth()->id(),
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);

            return redirect()->back()
                ->with('success', 'Password berhasil direset!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update user role (admin only)
     */
    public function updateRole(Request $request, User $user)
    {
        // Only admin can change roles
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()
                ->with('error', 'Hanya admin yang dapat mengubah role!');
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:' . implode(',', array_keys(User::getAllRoles())),
        ], [
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $oldRole = $user->role;
            $newRole = $request->role;

            // Prevent changing own role
            if (auth()->id() === $user->id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat mengubah role Anda sendiri!');
            }

            // Prevent removing last admin
            if ($user->isAdmin() && $newRole !== User::ROLE_ADMIN) {
                $adminCount = User::admins()->count();
                if ($adminCount <= 1) {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat mengubah role admin terakhir!');
                }
            }

            $user->update(['role' => $newRole]);

            // Log role change
            \Log::info('User role changed', [
                'changed_by' => auth()->id(),
                'user_id' => $user->id,
                'old_role' => $oldRole,
                'new_role' => $newRole,
            ]);

            return redirect()->back()
                ->with('success', "Role user berhasil diubah dari {$oldRole} menjadi {$newRole}!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Check if current user can manage target user
     */
    private function canManageUser(User $currentUser, User $targetUser): bool
    {
        // Admin can manage everyone except themselves for role changes
        if ($currentUser->isAdmin()) {
            return true;
        }

        // Manager can manage staff and regular users only
        if ($currentUser->isManager()) {
            return $targetUser->hasAnyRole([User::ROLE_STAFF, User::ROLE_USER]);
        }

        // Staff and users cannot manage others
        return false;
    }

    /**
     * Fix user timestamps if needed
     */
    private function fixUserTimestamps(User $user): void
    {
        $needsSave = false;
        
        if (!$user->created_at || !($user->created_at instanceof \Carbon\Carbon)) {
            $user->created_at = $user->updated_at ?? now();
            $needsSave = true;
        }
        
        if (!$user->updated_at || !($user->updated_at instanceof \Carbon\Carbon)) {
            $user->updated_at = now();
            $needsSave = true;
        }
        
        if ($user->email_verified_at && !($user->email_verified_at instanceof \Carbon\Carbon)) {
            try {
                $user->email_verified_at = \Carbon\Carbon::parse($user->email_verified_at);
                $needsSave = true;
            } catch (\Exception $e) {
                $user->email_verified_at = now();
                $needsSave = true;
            }
        }
        
        if ($needsSave) {
            $user->save();
        }
    }
}