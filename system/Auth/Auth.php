<?php

namespace System\Auth;

use App\User;
use System\Session\Session;


// وظیفه لاگین و چک کردن لاگین بودن کاربر و لاگ اوت شدن کاربر
class Auth
    {


    private $redirectTo = "/login";

    private function userMethod()
        {

        if (!Session::get('user')) {
            return redirect($this->redirectTo);
            }

        $user = User::find(Session::get('user'));

        if (empty($user)) {
            Session::remove('user');
            return redirect($this->redirectTo);
            }
        else
            return $user;

        }


    private function checkMethod()
        {

        if (!Session::get('user')) {
            return redirect($this->redirectTo);
            }

        $user = User::find(Session::get('user'));

        if (empty($user)) {
            Session::remove('user');
            return redirect($this->redirectTo);
            }
        else
            return true;

        }

    private function checkloginMethod()
        {

        if (!Session::get('user')) {
            return false;
            }

        $user = User::find(Session::get('user'));

        if (empty($user)) {
            return false;
            }
        else
            return true;
        }

    //-----------------------------------------
    //tester


    //ورود با ایمیل  و پسورد
    private function loginByEmailMethod($email, $password)
        {

        $user = User::where('email', $email)->get();
        if (empty($user)) {
            error('login', 'کاربر وجود ندارد');
            return false;
            }

        if (password_verify($password, $user[0]->password) && $user[0]->is_active == 1) {
            Session::set('user', $user[0]->id);
            return true;
            }
        else {
            error('login', 'پسورد اشتباه است');
            return false;
            }

        }
    //ورود با ایمیل و یوزرنیم و پسورد
    private function loginByEmailUsernameMethod($emailOruser, $password)
        {
        if (filter_var($emailOruser, FILTER_VALIDATE_EMAIL)) {
            // کاربر ایمیل وارد کرده
            $user = User::where('email', $emailOruser)->get();
            if (empty($user)) {
                error('login', 'کاربر وجود ندارد');
                return false;
                }
            }
        else {
            // کاربر یوزرنیم وارد کرده
            $user = User::where('username', $emailOruser)->get();
            if (empty($user)) {
                error('login', 'کاربر وجود ندارد');
                return false;
                }
            }
            
        if (password_verify($password, $user[0]->password) && $user[0]->is_active == 1) {
            Session::set('user', $user[0]->id);
            return true;
            }
        else {
            error('login', 'پسورد اشتباه است');
            return false;
            }
        }

    //ورود با ID
    private function loginByIdMethod($id)
        {

        $user = User::find($id);
        if (empty($user)) {
            error('login', 'کاربر وجود ندارد');
            return false;
            }
        else {
            Session::set('user', $user->id);
            return true;
            }

        }


    private function logoutMethod()
        {
        Session::remove('user');
        }

    //------------------------------------------

    public static function __callStatic($name, $arguments)
        {

        $instance = new self();
        return $instance->methodCaller($name, $arguments);

        }

    public function __call($name, $arguments)
        {

        return $this->methodCaller($name, $arguments);
        }

    private function methodCaller($medthod, $args)
        {

        $suffix = 'Method';
        $medthodName = $medthod . $suffix;
        return call_user_func_array(array($this, $medthodName), $args);
        }

    }