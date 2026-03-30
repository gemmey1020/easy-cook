<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\user_has_roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // عرض قائمة المستخدمين
    public function index()
    {
        $users = User::with('roles')->paginate('15');

        return view('dash.users.index', compact('users'));
    }

    // عرض صفحة إنشاء مستخدم جديد
    public function create()
    {
        $roles = Role::all(); // جلب جميع الأدوار

        return view('dash.users.create', compact('roles'));
    }

    // تخزين مستخدم جديد
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try{
            DB::beginTransaction();
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                foreach ($request->roles as $roleId) {
                    user_has_roles::create([
                        'user_id' => $user->id,
                        'role_id' => $roleId,
                    ]);
                }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    // عرض صفحة تعديل مستخدم
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('dash.users.edit', compact('user', 'roles'));
    }

    // تحديث بيانات المستخدم
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->update([
            'first_name' => $request->name,
            'email' => $request->email,
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
