<?php

namespace App\Http\Controllers\SuperAdmin\Management;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function list()
    {
        $admins = Admin::all();

        return view('super_admin.management.admin.list')->with([
            'admins' => $admins,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'string|max:2048|required',
            'email' => 'required|email|max:2048|unique:admins,email',
            'username' => 'required|string|max:2048|unique:admins,username'
        ]);

        $admin = new Admin;

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->password = Hash::make($request->username);

        $admin->save();

        return redirect(route('super_admin.management.admin.list'))->with('success', 'New Admin has been created');
    }

    public function view($id)
    {
        $admin = Admin::find($id);

        return view('super_admin.management.admin.view')->with([
            'admin' => $admin,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:2048|required',
            'email' => [
                'required',
                'email',
                'max:2048',
                Rule::unique('admins')->ignore($id)
            ],
            'username' => [
                'required',
                'string',
                'max:2048',
                Rule::unique('admins')->ignore($id)
            ],
        ]);

        $admin = Admin::find($id);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;

        $admin->save();

        return redirect(route('super_admin.management.admin.view', ['id' => $admin->id]))->with('success', 'This Admin has been successfully updated');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'admin_ids' => 'required|array',
            'admin_ids.*' => 'required|integer|exists:admins,id'
        ]);

        Admin::destroy($request->admin_ids);

        return redirect(route('super_admin.management.admin.list'))->with('success', 'Selected Admin has been successfully deleted');
    }
}
