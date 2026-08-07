<?php
namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Services\MailService;
use System\Config\Config;
use Exception;

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
        return view('admin.users.edit', compact('user'));
        }

    public function update($id)
        {
        $request = new UserRequest();
        $inputs = $request->all();
        $emailuser = User::where('email', $inputs['email'])->get();
        $usernameuser = User::where('username', $inputs['username'])->get();

        if (empty($request->send_info)) {
            $datauser = '';
            unset($inputs['send_info']);
            }
        else {
            $datauser = "<p>پسورد جدید شما : (" . $inputs['password'] . ")</p>
            <p>نام کاربری جدید شما : (" . $inputs['username'] . ")</p>";
            unset($inputs['send_info']);
            }

        if (empty($request->password)) {
            unset($inputs['password']);
            }
        else {
            $inputs['password'] = password_hash($inputs['password'], PASSWORD_DEFAULT);
            }
        if (empty($request->is_active)) {
            $inputs['is_active'] = 0;
            }
        else {
            $inputs['is_active'] = 1;
            }

        if (!empty($emailuser)) {
            $email = $inputs['email'];
            unset($inputs['email']);
            }
        else {
            $email = $inputs['email'];
            }

        if (!empty($usernameuser)) {
            $username = $inputs['username'];
            unset($inputs['username']);
            }
        else {
            $username = $inputs['username'];
            }

        // dd($inputs);
        if (User::update($inputs)) {

            $baseUrl = Config::get('app.BASE_URL');
            $appTitle = Config::get('app.APP_TITLE');
            require_once Config::get('app.BASE_DIR') . '/public/jdf/jdf.php';

            list($date, $time) = explode(' ', date('Y/m/d H:i:s'));
            list($y, $m, $d) = explode('/', $date);
            list($h, $i, $s) = explode(':', $time);

            $g = gregorian_to_jalali($y, $m, $d);

            $gregorian = sprintf(
                "%04d-%02d-%02d %02d:%02d:%02d",
                $g[0], $g[1], $g[2],
                $h, $i, $s
            );


            $messageEmail = <<<HTML
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تغیر رمز عبور</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: Tahoma, Arial, sans-serif;
            direction: rtl;
        }
        table {
            border-spacing: 0;
        }
        .email-wrapper {
            width: 100%;
            padding: 40px 15px;
            background-color: #f4f6f9;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.07);
        }
        .header {
            padding: 28px 30px;
            text-align: center;
            background-color: #111827;
        }
        .logo {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }
        .content {
            padding: 45px 40px;
            text-align: center;
        }
        .icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 25px;
            border-radius: 50%;
            background-color: #eef2ff;
            color: #4f46e5;
            font-size: 32px;
            line-height: 70px;
        }
        .title {
            margin: 0 0 15px;
            color: #111827;
            font-size: 26px;
            font-weight: bold;
        }
        .description {
            margin: 0 auto;
            max-width: 450px;
            color: #6b7280;
            font-size: 15px;
            line-height: 2;
        }
        .button-wrapper {
            margin: 32px 0;
        }
        .button {
            display: inline-block;
            padding: 15px 35px;
            border-radius: 10px;
            background-color: #4f46e5;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
        }
        .expire-box {
            margin-top: 25px;
            padding: 15px 20px;
            border-radius: 10px;
            background-color: #fff7ed;
            color: #c2410c;
            font-size: 13px;
            line-height: 1.9;
        }
        .security {
            margin-top: 28px;
            padding-top: 25px;
            border-top: 1px solid #eeeeee;
            color: #9ca3af;
            font-size: 12px;
            line-height: 2;
        }
        .footer {
            padding: 25px 30px;
            background-color: #f9fafb;
            text-align: center;
            color: #9ca3af;
            font-size: 11px;
            line-height: 2;
        }
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                padding: 20px 10px;
            }
            .content {
                padding: 35px 22px;
            }
            .title {
                font-size: 22px;
            }
            .description {
                font-size: 14px;
            }
            .button {
                display: block;
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td class="email-wrapper">
            <table
                role="presentation"
                class="email-container"
                cellpadding="0"
                cellspacing="0"
                align="center"
            >
                <tr>
                    <td class="header">
                        <a
                            href="{$baseUrl}"
                            class="logo"
                        >
                            {$appTitle}
                        </a>
                    </td>
                </tr>
                <!-- Content -->
                <tr>
                    <td class="content">
                        <div class="icon">
                            🔐
                        </div>
                        <h1 class="title">
                            ویرایش اطلاعات توسط ادمین
                        </h1>
                        <p class="description" style="direction:rtl">
                            {$inputs['first_name']} {$inputs['last_name']} عزیز، سلام.
                            <br>
                            اطلاعات شما توسط ادمین ویرایش شد.
                        </p>
                        {$datauser}
                    </td>
                </tr>
                <tr>
                    <td class="footer">
                        این ایمیل به صورت خودکار ارسال شده است.
                        لطفاً به آن پاسخ ندهید.
                        <br>
                        © {$gregorian} {$appTitle}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
HTML;

            try {
                $serviceMail = new MailService();
                $serviceMail->send($email, 'تغیر اطلاعات توسط ادمین', $messageEmail);
                flash('edit_user', 'اطلاعات با موفقیت تغیر کرد');
                // return redirect($this->redirectTo);
                return back();
                } catch (Exception $e) {
                echo ('مشکل در ارسال ایمیل ') . $e->getMessage();
                die();
                }
            }
        else {
            error('edit_user', 'مشکل ذخیره سازی در دیتابیس');
            return back();
            }

        }

    public function destroy($id)
        {
        User::delete($id);
        return back();
        }


    }