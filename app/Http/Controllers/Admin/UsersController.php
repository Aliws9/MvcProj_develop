<?php
namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Requests\Admin\UserRequest;

class UsersController extends AdminController
    {

    public function index()
        {
        $users = User::all();
        return view('admin.users.index', compact('users'));
        }

    public function type($type)
        {
        switch ($type) {
            case 'all':
                $users = User::all();
                return view('admin.users.index', compact('users'));
            case 'admin2':
                $users = User::where('user_type', 'admin2')->get();
                return view('admin.users.user_type', compact('users'));
            case 'admin_seo':
                $users = User::where('user_type', 'admin_seo')->get();
                return view('admin.users.user_type', compact('users'));
            case 'editor':
                $users = User::where('user_type', 'editor')->get();
                return view('admin.users.user_type', compact('users'));
            case 'normal':
                $users = User::where('user_type', 'normal')->get();
                return view('admin.users.user_type', compact('users'));
            }
        }

    // user type

    public function create()
        {
        return view('admin.users.create');
        }
    public function store()
        {

        $request = new UserRequest();
        $inputs = $request->all();

        if (!empty($inputs['send_mail'])) {
            unset($inputs['send_mail']);
            }
        if ($inputs['is_active'] == true) {
            $inputs['is_active'] = 1;
            }
        else {
            $inputs['is_active'] = 0;
            }
        User::create($inputs);
        return redirect('admin/users');
        }
    public function edit($id)
        {
            $user = User::find($id);
        return view('admin.users.edit' , compact('user'));
        }

    public function update($id)
        {

        }

    public function destroy($id)
        {

        }


    }