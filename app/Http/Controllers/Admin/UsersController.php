<?php
namespace App\Http\Controllers\Admin;

use App\User;

class UsersController extends AdminController
    {

    public function index()
        {
            $users = User::all();
            return view('admin.users.index' , compact('users'));
        }

    public function create()
        {

        }
    public function store()
        {


        }
    public function edit($id)
        {

        }

    public function update($id)
        {

        }

    public function destroy($id)
        {

        }


    }