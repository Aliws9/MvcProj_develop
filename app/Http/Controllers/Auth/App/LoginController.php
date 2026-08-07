<?php

namespace App\Http\Controllers\Auth\App;

use App\Http\Services\MailService;

use App\User;
use System\Auth\Auth;
use App\Http\Requests\Auth\App\LoginRequest;
//use System\Config\Config;
// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver;

class LoginController
    {
    private $redirectTo = '/login';
    private $redirectToAdmin = '/admin';

    public function view()
        {
        return view('auth.app.login');
        }

        public function login(){
            //dd('error');
            Auth::logout();
            $request = new LoginRequest();

            if(Auth::loginByEmailUsername($request->emailOrusername , $request->password)){
                $user = User::where('email' , $request->emailOrusername)->whereOr('username' , $request->emailOrusername)->get();
                $user = $user[0];
                if($user->user_type == 'admin' OR $user->user_type == 'editor' OR $user->user_type == 'admin_seo' OR $user->user_type == 'admin2'){
                    return redirect($this->redirectToAdmin);
                }else{
                    return redirect($this->redirectTo);
                }
            }else{
                return back();
            }
        }

    }