<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;


class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(){

        $users = User::select('id','name', 'username','email')->where('status','aktif')->paginate(10);
        return view('backend.user', compact('users'));
    }

    public function create(){
        $roles = Role::select('id','name')->get();
        return view('backend.userCreate',compact('roles'));
    }

    public function store(UserStoreRequest $request){
        $this->userService->createUser($request->validated());

        return redirect()->route('user')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user){
        $roles = Role::select('id','name')->get();
        $userRoleId = $user->roles()->first()?->id;

        return view('backend.userEdit',compact('user', 'roles', 'userRoleId'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {

        $this->userService->updateUser($request->validated(), $user);

        return redirect()->route('user')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Proteksi: Jangan biarkan user menonaktifkan dirinya sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('user')
                ->with('error', 'Anda tidak bisa menonaktifkan akun sendiri.');
        }

        $this->userService->deleteUser($user);

        return redirect()->route('user')
            ->with('success', 'User berhasil dinonaktifkan.');
    }

}
