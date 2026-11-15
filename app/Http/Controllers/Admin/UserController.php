<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    protected array $adminEmails = [
        'bogdadishahali@gmail.com',
        'admin@bongosoftbd.com',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $roleIds = Role::pluck('id');

        // Here bogdadishahali@gmail.com is admin from bongosoft and kept hidden due to system security.
        $users = User::withTrashed()->with('role')->whereNotIn('email', $this->adminEmails)->whereIn('role_id', $roleIds);

        if ($search) {
            $users = $users
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        $users = $users
            ->paginate(15)
            ->withQueryString();

        return view('admin.user.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::query()->whereNot('id', 1)->where([['status', StatusEnum::ACTIVE->value]])->get();
        return view('admin.user.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'          => 'required',
                'email'         => 'required|email|unique:users', // TODO:: If user is removed by softDelete then email should be unique
                'role_id'       => 'required',
                'password'      => 'required|confirmed|min:6',
                'photo'         => 'image',
            ],
            [
                'role_id.required' => 'Required',
            ]
        );

        $user = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->role_id      = $request->role_id;
        $user->password     = Hash::make($request->password);
        $user->phone        = $request->phone;

        if ($request->photo) {
            $user->photo = FileUploadHelper::uploadFile($request->photo, $user->photo);
        }

        $user->save();
        return redirect()->route('user.index')->with('message', 'New user added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (in_array($user->email, $this->adminEmails)) {
            return redirect()->route('user.index')->with('error', 'Please contact your system admin! The user can not be updated.');
        }

        $roles = Role::query()
            ->where([['status', StatusEnum::ACTIVE->value]])
            ->get();

        return view('admin.user.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (in_array($user->email, $this->adminEmails)) {
            return redirect()->route('user.index')->with('error', 'Please contact your system admin! The user can not be updated.');
        }

        $validatedData = $request->validate(
            [
                'name'          => 'required',
                'email'         => 'required|email|unique:users,email,' . $user->id,
                'role_id'       => 'required',
                'password'      => $request->password ? 'confirmed|min:6' : '',
                'photo'         => 'image',
                'status'        => 'required',
            ],
            [
                'role_id.required' => 'Required',
            ]
        );

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->role_id  = $request->role_id;
        $user->phone    = $request->phone;
        $user->status   = $request->status;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->photo) {
            $user->photo = FileUploadHelper::uploadFile($request->photo, $user->photo);
        }

        $user->update();

        if (auth()->id() == $user->id) {
            Session::flush();
            Auth::logout();
            return redirect()->route('loginForm')->with('error', 'Please login again.');
        }
        return redirect()->route('user.index')->with('message', 'Successfully user information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (in_array($user->email, $this->adminEmails)) {
            return redirect()->route('user.index')->with('error', 'Please contact your system admin! The user can not be updated.');
        }

        if (auth()->user()->role_id == 1) {
            // Mark user as inactive
            $user->status = StatusEnum::INACTIVE->value;
            $user->save();

            // Soft delete the user
            $user->delete();

            return redirect()->route('user.index')
                ->with('message', 'User has been deleted from this system!');
        }

        return redirect()->route('user.index')
            ->with('permissionError', Config::get('helper.permissionErrorMsg'));
    }

    /**
     * Show the form for editing the user change-password in storage.
     */
    public function userPasswordEdit(User $user)
    {
        if ($user && $user->role_id == 1) {
            return view('admin.user.changePassword', ['user' => $user]);
        }
        return redirect()->route('user.index')->with('error', 'Invalid user. No user found');
    }

    /**
     * Update the user change-password in storage.
     */
    public function userPasswordUpdate(Request $request, $id)
    {
        $user = User::find($id);
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()
            ->route('user.index')
            ->with('message', 'Password has been changed for the User');
    }

    /**
     * Restore soft-deleted user.
     */
    public function userRestore(User $user)
    {
        if ($user) {
            $user->restore();
            return redirect()->route('user.index')->with('message', 'User has been restored from this system!');
        }
        return redirect()->route('user.index')->with('error', 'Invalid user. No user found');
    }
}
