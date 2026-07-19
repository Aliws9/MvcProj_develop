@extends('admin.layouts.page')

<head>
    @section('head-tag')
        <?php use System\Config\Config; ?>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ثبت‌نام</title>
        <link rel="stylesheet" href="<?php echo asset('tailwind/output.css'); ?>">
        <script src="<?= asset('flyonui/flyonui.js'); ?>"></script>

        <style>
            @keyframes float {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-20px);
                }
            }

            @keyframes gradientBG {
                0% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }

            .bg-animated-gradient {
                background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
            }

            .floating {
                animation: float 6s ease-in-out infinite;
            }

            .avatar-upload-box {
                transition: all .2s ease;
            }

            .avatar-upload-box:hover {
                transform: scale(1.03);
            }

            .avatar-preview-img {
                filter: drop-shadow(0 10px 15px rgba(0, 0, 0, .3));
            }
        </style>
    @endsection
</head>
@section('content')


    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-20 w-16 h-16 rounded-full bg-white/10 backdrop-blur-sm floating"
            style="animation-delay:0s"></div>
        <div class="absolute top-1/3 right-1/4 w-24 h-24 rounded-full bg-white/15 backdrop-blur-sm floating"
            style="animation-delay:1s"></div>
        <div class="absolute bottom-20 left-1/4 w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm floating"
            style="animation-delay:2s"></div>
        <div class="absolute top-1/4 right-20 w-12 h-12 rounded-full bg-white/10 backdrop-blur-sm floating"
            style="animation-delay:3s"></div>
    </div>

    <div class="relative w-full md:w-[70%] lg:w-2/6">
        <div class="bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">

                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-white mb-2">ایجاد حساب کاربری</h1>
                    <p class="text-white/80">برای ثبت‌نام اطلاعات زیر را تکمیل کنید</p>
                </div>

                <!-- پیام‌های خطا -->
                <?php
    $errorEmail = errorClass('email');
    $errorPassword = errorClass('password');
    $errorFirstName = errorClass('first_name');
    $errorLastName = errorClass('last_name');
    $errorAvatar = errorClass('avatar');
                    ?>

                <div class="<?= $errorEmail['class_error']; ?> alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert" id="dismiss-alert-error-email">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm"><?= $errorEmail['message_error']; ?></p>
                    <button type="button" class="ms-auto cursor-pointer" data-remove-element="#dismiss-alert-error-email">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>

                <div class="<?= $errorPassword['class_error']; ?> alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert" id="dismiss-alert-error-password">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm"><?= $errorPassword['message_error']; ?></p>
                    <button type="button" class="ms-auto cursor-pointer"
                        data-remove-element="#dismiss-alert-error-password">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>

                <div class="<?= $errorFirstName['class_error']; ?> alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert" id="dismiss-alert-error-first-name">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm"><?= $errorFirstName['message_error']; ?></p>
                    <button type="button" class="ms-auto cursor-pointer"
                        data-remove-element="#dismiss-alert-error-first-name">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>

                <div class="<?= $errorLastName['class_error']; ?> alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert" id="dismiss-alert-error-last-name">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm"><?= $errorLastName['message_error']; ?></p>
                    <button type="button" class="ms-auto cursor-pointer"
                        data-remove-element="#dismiss-alert-error-last-name">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>

                <div class="<?= $errorAvatar['class_error']; ?> alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert" id="dismiss-alert-error-avatar">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm"><?= $errorAvatar['message_error']; ?></p>
                    <button type="button" class="ms-auto cursor-pointer" data-remove-element="#dismiss-alert-error-avatar">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>

                <!-- فرم ثبت‌نام: تمام فیلدها هم‌نام با ستون‌های جدول users -->
                <form action="<?= route('app.auth.register'); ?>" method="post" enctype="multipart/form-data" id="registerForm">

                    <!-- تصویر پروفایل -->
                    <div class="mb-6">
                        <label class="block text-white/90 text-sm mb-2 text-center">تصویر پروفایل</label>

                        <div class="flex justify-center">
                            <div class="relative">
                                <!-- پیش‌نمایش تصویر -->
                                <div id="avatarPreviewWrap" class="hidden relative">
                                    <img id="avatarPreview"
                                        class="avatar-preview-img w-32 h-32 rounded-full object-cover border-4 border-white/30"
                                        src="" alt="پیش‌نمایش تصویر">

                                    <!-- دکمه تغییر تصویر -->
                                    <button type="button" id="changeAvatarBtn"
                                        class="absolute -bottom-1 -left-1 bg-indigo-500 hover:bg-indigo-600 rounded-full p-2 shadow-lg transition flex items-center justify-center content-center"
                                        aria-label="تغییر تصویر" title="تغییر تصویر">
                                        <span class="icon-[tabler--pencil] size-4 text-white"></span>
                                    </button>

                                    <!-- دکمه حذف تصویر -->
                                    <button type="button" id="removeAvatarBtn"
                                        class="absolute -bottom-1 -right-1 bg-red-500 hover:bg-red-600 rounded-full p-2 shadow-lg transition flex items-center justify-center content-center"
                                        aria-label="حذف تصویر" title="حذف تصویر">
                                        <span class="icon-[tabler--x] size-4 text-white"></span>
                                    </button>
                                </div>

                                <!-- باکس آپلود اولیه -->
                                <div id="avatarUploadBox"
                                    class="avatar-upload-box w-32 h-32 rounded-full border-2 border-dashed border-white/40 flex flex-col items-center justify-center cursor-pointer hover:border-white/70 transition">
                                    <span class="icon-[tabler--camera-plus] size-8 text-white mb-1"></span>
                                    <span class="text-white/70 text-xs">انتخاب تصویر</span>
                                </div>
                            </div>
                        </div>

                        <input type="file" id="avatarInput" name="avatar" class="hidden"
                            accept="image/png,image/jpeg,image/jpg">
                        <p class="text-white/60 text-xs text-center mt-2">فرمت مجاز: JPG یا PNG - حداکثر ۵ مگابایت</p>
                    </div>
                    

                    <!-- نام و نام خانوادگی -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="relative">
                            <input type="text" name="first_name" value="<?= old('first_name'); ?>"
                                class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition"
                                placeholder="نام" required>
                        </div>
                        <div class="relative">
                            <input type="text" name="last_name" value="<?= old('last_name'); ?>"
                                class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition"
                                placeholder="نام خانوادگی" required>
                        </div>
                    </div>

                    <!-- ایمیل -->
                    <div class="relative mb-4">
                        <input type="email" name="email" value="<?= old('email'); ?>"
                            class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 ps-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition"
                            placeholder="آدرس ایمیل" required>
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <span class="icon-[tabler--mail] size-5 text-white/70"></span>
                        </div>
                    </div>

                    <!-- رمز عبور -->
                    <div class="relative mb-4">
                        <input type="password" name="password" id="passwordInput"
                            class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 ps-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition toggle-password-checkbox1"
                            placeholder="رمز عبور (حداقل ۸ کاراکتر)" minlength="8" value="<?= old('password'); ?>" required>
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <span class="icon-[tabler--lock] size-5 text-white/70"></span>
                        </div>

                    </div>

                    <!-- تکرار رمز عبور -->
                    <div class="relative mb-6">
                        <input type="password" name="password_confirmation" id="passwordConfirmInput"
                            class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 ps-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition toggle-password-checkbox"
                            placeholder="تکرار رمز عبور" minlength="8" value="<?= old('password'); ?>" required>
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <span class="icon-[tabler--lock-check] size-5 text-white/70"></span>
                        </div>
                    </div>

                    <div class="relative mb-6">
                        <div class="flex items-center gap-2">
                            <input id="toggleCheckboxPassword" type="checkbox"
                                data-toggle-password='{ "target": ["#passwordInput" , "#passwordConfirmInput"] }'
                                class="checkbox checkbox-primary !rounded-full" style="border:1px solid white" />
                            <label class="label-text !text-white" for="toggleCheckboxPassword">نمایش رمز عبور</label>
                        </div>
                    </div>



                    <button type="submit"
                        class="w-full bg-white text-indigo-600 py-3 px-4 rounded-lg font-semibold hover:bg-white/90 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-500 transition-all transform hover:scale-105 active:scale-95">
                        ثبت‌نام
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-white/60 text-sm">قبلاً ثبت‌نام کرده‌اید؟ <a href="<?= url('login'); ?>"
                    class="text-white hover:underline">وارد شوید</a></p>
        </div>
    </div>

    <script>
        (function () {
            const uploadBox = document.getElementById('avatarUploadBox');
            const fileInput = document.getElementById('avatarInput');
            const previewWrap = document.getElementById('avatarPreviewWrap');
            const previewImg = document.getElementById('avatarPreview');
            const changeBtn = document.getElementById('changeAvatarBtn');
            const removeBtn = document.getElementById('removeAvatarBtn');

            function openFileDialog() {
                fileInput.click();
            }

            // باز کردن انتخاب فایل با کلیک روی باکس اولیه یا دکمه تغییر تصویر
            uploadBox.addEventListener('click', openFileDialog);
            changeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                openFileDialog();
            });

            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (event) {
                    previewImg.src = event.target.result;
                    previewWrap.classList.remove('hidden');
                    uploadBox.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });

            // حذف تصویر انتخاب‌شده و بازگشت به حالت اولیه
            removeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                fileInput.value = '';
                previewImg.src = '';
                previewWrap.classList.add('hidden');
                uploadBox.classList.remove('hidden');
            });

            // اعتبارسنجی سادهٔ سمت کلاینت برای برابر بودن رمزها قبل از ارسال
            document.getElementById('registerForm').addEventListener('submit', function (e) {
                const pass = document.getElementById('passwordInput').value;
                const confirm = document.getElementById('passwordConfirmInput').value;
                if (pass !== confirm) {
                    e.preventDefault();
                    alert('رمز عبور و تکرار آن یکسان نیستند.');
                }
            });
        })();
    </script>
@endsection