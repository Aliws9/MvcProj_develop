<?php

namespace App\Http\Controllers\Auth\App;

use App\Http\Requests\Auth\App\ForgotRequest;
use App\Http\Services\MailService;
use App\User;
use Exception;
use System\Session\Session;
use System\Config\Config;

class ForgotController
    {
    private $redirectTo = '/home';

    public function view()
        {
        return view('auth.app.forgot');
        }

    public function forgot()
        {
        Session::remove('forgot.time');
        if (Session::get('forgot.time') != false && Session::get('forgot.time') > time()) {
            error('forgot', 'لطفا 2 دقیقه دیگر امتحان کنید.');
            //dd('hi');
            }
        else {
            Session::set('forgot.time', time() + 120);
            $request = new ForgotRequest();

            $input = $request->all();
            $user = User::where('email', $input['emailOruser'])->whereOr('username', $input['emailOruser'])->get();
            //dd($user);
            if (empty($user)) {
                error('forgot', 'کاربر وجود ندارد');
                return back();
                }
            else {
                $user = $user[0];
                $userEmail = $user->email;
                }
            $baseUrl = Config::get('app.BASE_URL');
            $appTitle = Config::get('app.APP_TITLE');
            $user->remember_token = generateToken();
            $user->remember_token_expire = date("Y-m-d H:i:s", strtotime(' + 15 min'));
            require_once Config::get('app.BASE_DIR') . '/public/jdf/jdf.php';

            list($y, $m, $d) = explode('-', date('Y-m-d'));

            $date = gregorian_to_jalali($y, $m, $d);

            $jalaliDate = sprintf(
                '%04d-%02d-%02d',
                $date[0],
                $date[1],
                $date[2]
            );

            // لینک بازیابی رمز عبور
            $resetUrl = route('auth.app.reset_password', [$user->remember_token]);

            if ($user->save()) {
                // ساخت HTML ایمیل
                $messageEmail = <<<HTML
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>بازیابی رمز عبور</title>

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

                <!-- Header -->

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
                            بازیابی رمز عبور
                        </h1>


                        <p class="description">

                            {$user->first_name} {$user->last_name} عزیز، سلام.

                            <br>

                            درخواست بازیابی رمز عبور حساب کاربری شما دریافت شده است.
                            برای تعیین رمز عبور جدید، روی دکمه زیر کلیک کنید.

                        </p>


                        <!-- Button -->

                        <div class="button-wrapper">

                            <a
                                href="{$resetUrl}"
                                class="button"
                                target="_blank"
                            >
                                بازیابی رمز عبور
                            </a>

                        </div>


                        <!-- Expiration -->

                        <div class="expire-box">

                            ⏱️ این لینک فقط به مدت
                            <strong>۱۵ دقیقه</strong>
                            معتبر است و پس از آن منقضی خواهد شد.

                        </div>


                        <!-- Security -->

                        <div class="security">

                            اگر شما درخواست بازیابی رمز عبور نداده‌اید،
                            می‌توانید این ایمیل را نادیده بگیرید.

                            <br>

                            رمز عبور حساب شما تغییر نخواهد کرد.

                        </div>

                    </td>

                </tr>


                <!-- Footer -->

                <tr>

                    <td class="footer">

                        این ایمیل به صورت خودکار ارسال شده است.
                        لطفاً به آن پاسخ ندهید.

                        <br>

                        © {$jalaliDate} {$appTitle}

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
                    $serviceMail->send($userEmail, 'بازیابی رمز عبور شما', $messageEmail);
                    flash('forgot', 'ایمیل بازیابی با موفقیت به ایمیل شما ارسال شد');
                    // return redirect($this->redirectTo);
                    return back();
                    } catch (Exception $e) {
                    echo ('مشکل در ارسال ایمیل ') . $e->getMessage();
                    die();
                    }

                }
            else {
                die('مشکل در دیتابیس');
                }
            }
        }

    }