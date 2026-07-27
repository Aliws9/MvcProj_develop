<?php

namespace App\Http\Controllers\Auth\App;

use App\Http\Requests\Auth\App\RegisterRequest;
use App\Http\Services\MailService;
use App\User;
use System\Config\Config;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RegisterController
    {
    private $redirectTo = '/login';
    private $redirectWait = 'login/WaitActive';

    public function view()
        {
        return view('auth.app.register');
        }

    /**
     * آپلود تصویر پروفایل با FilePond
     * FilePond یک POST می‌فرسته و انتظار داره یک string (شناسه/مسیر) برگرده
     * تا بعدا در فیلد مخفی فرم ثبت‌نام قرار بگیره
     */
    public function uploadAvatar()
        {
        if (!isset($_FILES['filepond'])) {
            http_response_code(400);
            echo json_encode(['error' => 'فایلی ارسال نشده است']);
            exit;
            }

        $file = $_FILES['filepond'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['error' => 'خطا در آپلود فایل']);
            exit;
            }

        $allowedExt = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            http_response_code(422);
            echo json_encode(['error' => 'فرمت فایل مجاز نیست']);
            exit;
            }

        if ($file['size'] > 5 * 1024 * 1024) {
            http_response_code(422);
            echo json_encode(['error' => 'حجم فایل بیشتر از حد مجاز است']);
            exit;
            }

        $datePath = date('Y/m/d');
        $uploadDir = dirname(__DIR__, 5) . '/public/upload/avatars/' . $datePath;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
            }

        $uniqueName = date('Y_m_d_H_i_s_') . uniqid() . '.' . $ext;
        $destination = $uploadDir . '/' . $uniqueName;

        $width = 400;

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file['tmp_name'])->scale($width);
        $image->save($destination);

        $relativePath = Config::get('app.BASE_URL') . '/upload/avatars/' . $datePath . '/' . $uniqueName;

        // FilePond مقدار برگشتی رو به عنوان serverId نگه می‌داره
        // همون مقداری هست که در هنگام حذف (revert) برای ما ارسال می‌شود
        header('Content-Type: text/plain');
        echo $relativePath;
        exit;
        }

    /**
     * حذف تصویر آپلود شده - FilePond یک DELETE می‌فرسته با serverId (بدنه‌ی درخواست)
     */
    public function deleteAvatar()
        {
        $relativePath = trim(file_get_contents('php://input'));

        if ($relativePath === '') {
            http_response_code(400);
            exit;
            }

        // فقط اجازه‌ی حذف فایل‌های داخل پوشه‌ی آپلود آواتار را می‌دهیم
        $relativePath = str_replace(['..', "\0"], '', $relativePath);

        if (strpos($relativePath, 'upload/avatars/') !== 0) {
            http_response_code(400);
            exit;
            }

        $fullPath = dirname(__DIR__, 5) . '/public/' . $relativePath;

        if (file_exists($fullPath)) {
            unlink($fullPath);
            }

        http_response_code(200);
        exit;
        }

    public function register()
        {
        ob_start(); // شروع بافر
        $request = new RegisterRequest();
        $inputs = $request->all();

        if ($inputs['confirm_password'] == $inputs['password']) {
            unset($inputs['confirm_password']);
            $inputs['password'] = password_hash($inputs['password'], PASSWORD_DEFAULT);
            $inputs['is_active'] = 0;
            $inputs['user_type'] = 'normal';
            $inputs['verify_token'] = generateToken();
            $inputs['status'] = 1;
            }
        else {
            die('رمز عبور با تکرار آن برابر نیست.');
            }

        $message_email = "
<html dir='rtl'>
<style>
    @font-face {
        font-family: 'yekan';

        src: url('/upload/font/yekan-font/Yekan.eot');
        src:
            url('/upload/font/yekan-font/Yekan.eot?#iefix') format('embedded-opentype'),
            url('/upload/font/yekan-font/Yekan.woff') format('woff'),
            url('/upload/font/yekan-font/Yekan.ttf') format('truetype'),
            url('/upload/font/yekan-font/Yekan.svg#yekan') format('svg');

        font-weight: normal;
        font-style: normal;
    }

    body {
        font-family: 'yekan' !important;
        background-color: aliceblue;
        direction: rtl !important;
    }
    .button-b:hover{
        background-color: #3a86ff;
    }
</style>

<body style='background-color: aliceblue;'>
    <div class='container' style='width: 99%;
        margin: auto;
        background-color: white;
            box-sizing: border-box;
        padding: 20px;
        border-radius: 4px;
        box-shadow: rgba(0, 0, 0, 0.09) 0px 3px 12px;
        text-align: center;'>
        <div class='header-mail' style='padding: 1px;
        background-color: #ff9770;
        width: 97%;
        border-radius: 5px;
        text-align: center;
        color: white;
        margin: auto;'>
            <h1 class='h1-mail'>فعال سازی اکانت</h1>
        </div>
        <div class='content' style='width: 90%;
        margin: auto;
        background-color: #FEF9EF;
        padding: 7px;
        margin-top: 15px;
        border-radius: 10px;
        border: 2px solid #a5e6d7;'>
            <div class='main-content' style='text-align: center;
        color: #227C9D;direction: rtl;'>
                <p>دوست گرامی " . $inputs['first_name'] . ' ' . $inputs['last_name'] . " عزیز با نام کاربری " . $inputs['username'] . " , ممنون که عضو خانواده ما شدید.</p>
                <p>برای فعال سازی اکانت خود روی دکمه زیر کلیک کنید تا اکانت خود را فعال کنید و وارد شوید.</p>
            </div>
            <div class='button-container' style='width: 100%;
        text-align: center;
        padding: 10px;'>
                <a href='" . route('auth.app.activation', [$inputs['verify_token']]) . "' class='button-b' style='margin: auto;
        display: block;
        width: 60%;
        padding: 5px;
        background-color: #8338ec;
        color: white;
        border-radius: 7px;
        transition: background-color 200ms;'>فعال سازی</a>
            </div>
        </div>
        <br>
    </div>

</body>
</html>";

        User::create($inputs);
        $mailService = new MailService();
        $mailService->send($inputs['email'], 'ایمیل فعال سازی', $message_email);

        ob_end_clean(); // پاک کردن هر خروجی اضافه قبل از JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success'  => true,
            'redirect' => $this->redirectWait,
        ]);
        exit;
        }

    public function activation($token)
        {
        $user = User::where('verify_token', $token)->get();
        if (empty($user)) {
            die('توکن شما اعتبار ندارد.');
            }
        $user = $user[0];
        if ($user->status == 1) {
            $user->is_active = 1;
            $user->save();
            }
        else {
            die('شما توسط ادمین مسدود شده اید.');
            }
        return redirect('login');
        }

    //login controller
    public function LoginWaitActive()
        {
        dd('hi wait', false);
        return view('auth.app.login_wait');
        }

    }