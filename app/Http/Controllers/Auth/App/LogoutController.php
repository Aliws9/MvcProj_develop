<?php
namespace App\Http\Controllers\Auth\App;

use System\Auth\Auth;


class LogoutController
    {
    private $redirectTo = "/login";

    public function logout()
        {
            Auth::logout();
            return redirect($this->redirectTo);
        }

    }