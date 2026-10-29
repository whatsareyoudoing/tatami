<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view_user', only: ['index']),
            new Middleware('can:create_user', only: ['create', 'store']),
            new Middleware('can:update_user', only: ['edit', 'update']),
            new Middleware('can:delete_user', only: ['destroy']),
        ];
    }

    public function __construct(
        protected UserService $userService
    ) {}

    // Menu Pengguna khusus staf (admin, editor). Customer ada di menu Pelanggan.
    public function index(){

        $users = User::select('id','name', 'username','email')
            ->staff()
            ->with('roles:id,name')
            ->where('status','aktif')
            ->paginate(10);
        return view('backend.user', compact('users'));
    }

    public function create(){
        $roles = Role::staff()->select('id','name')->get();
        return view('backend.userCreate',compact('roles'));
    }

    public function store(UserStoreRequest $request){
        $this->userService->createUser($request->validated());

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user){
        $roles = Role::staff()->select('id','name')->get();
        $userRoleId = $user->roles()->first()?->id;

        return view('backend.userEdit',compact('user', 'roles', 'userRoleId'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {

        $this->userService->updateUser($request->validated(), $user);

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Proteksi: Jangan biarkan user menonaktifkan dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak bisa menonaktifkan akun sendiri.');
        }

        if ($user->isLastActiveAdmin()) {
            return redirect()->route('user.index')
                ->with('error', 'Admin terakhir tidak bisa dinonaktifkan.');
        }

        $this->userService->deleteUser($user);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dinonaktifkan.');
    }

}
