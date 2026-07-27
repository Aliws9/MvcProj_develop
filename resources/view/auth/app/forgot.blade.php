@extends('admin.layouts.page')

<head>
    @section('head-tag')

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>فراموشی رمز عبور</title>
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

            /* FilePond را در یک باکس گرد و کوچک محدود می‌کنیم */
            .avatar-filepond-wrap .filepond--root {
                width: 200px;
                height: 150px;
                margin: 0 auto;
            }

            .filepond--list-scroller {
                overflow-y: hidden !important;
            }

            .filepond--list.filepond--list {
                position: static !important;
            }

            .avatar-filepond-wrap .filepond--panel-root {
                border-radius: 9px;
                background-color: rgba(255, 255, 255, .15);
                border: 2px dashed rgba(255, 255, 255, .4);
            }

            .avatar-filepond-wrap .filepond--drop-label {
                color: rgba(255, 255, 255, .8);
                font-size: .75rem;
            }

            .avatar-filepond-wrap .filepond--image-preview-wrapper {
                border-radius: 9px;
            }

            .avatar-filepond-wrap .filepond--file-action-button {
                background-color: rgba(0, 0, 0, .5);
            }

            .avatar-filepond-wrap .filepond--credits {
                display: none;
            }

            #submitOverlay {
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, .55);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            }

            #submitOverlay.active {
                display: flex;
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

    <div class="relative w-full md:w-[70%] lg:w-3/11">
        <div class="bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-5">

                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-white mb-2">بازیابی رمز عبور</h1>
                    <p class="text-white/80">برای بازیابی رمز عبور فراموش شده ایمیل یا نام کاربری خودرا وارد کنید</p>
                </div>

                <!-- پیام‌های خطا -->
                <?php
    if (errorExist()) {
        foreach (allErorrs() as $key => $error) {
                                                                            ?>
                <div class="dismiss-alert-error-<?= $key ?> alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert" id="dismiss-alert-error-<?= $key ?>">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm"><?= $error ?></p>
                    <button type="button" class="ms-auto cursor-pointer"
                        data-remove-element="#dismiss-alert-error-<?= $key ?>">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>
                <?php            }
        }
    ;
    if (flashExists('forgot')) {
        foreach(allFlashes() as $keyFlash => $valueFlash){
                    ?>
                <div class="dismiss-alert-success-<?= $keyFlash ?> alert alert-success flex items-center gap-3 mb-3" role="alert"
                    id="dismiss-alert-success-<?= $keyFlash ?>">
                    <span class="icon-[tabler--circle-check] shrink-0 size-6"></span>
                    <p class="text-sm"><?= $valueFlash ?></p>
                    <button type="button" class="ms-auto cursor-pointer"
                        data-remove-element="#dismiss-alert-success-<?= $keyFlash ?>">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>
                <?php
        }
            }
                 ?>

                <!-- پیام خطای عمومی برای ارسال AJAX -->
                <div id="ajaxErrorAlert" class="hidden alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm" id="ajaxErrorMessage">خطایی رخ داد. دوباره تلاش کنید.</p>
                </div>

                <!-- فرم ثبت‌نام: تمام فیلدها هم‌نام با ستون‌های جدول users -->
                <form id="registerForm" action="<?= route('auth.app.forgot') ?>" method="post">



                    <!-- یوزر نیم -->
                    <div class="relative mb-5">
                        <input type="text" name="emailOruser"
                            class="w-full bg-white/20 text-white placeholder-white/50 rounded py-1.5 pr-8 pl-3 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition text-sm md:text-md border-[#dcdfe5] md:py-2.5 md:pr-10 md:pl-3"
                            placeholder="نام کاربری یا ایمیل (لاتین)" required>
                        <div class="absolute inset-y-0 start-0 ps-2 flex items-center pointer-events-none">
                            <span class="icon-[tabler--user] xs:size-5 md:size-7 text-white/60"></span>
                        </div>
                    </div>

                        <button type="submit" id="submitBtn" class="w-full btn btn-primary !btn-sm md:!btn-md">
                            ارسال
                        </button>
                </form>


                <div class="mt-2 text-center">
                    <p class="text-white/60 text-sm"><a href="<?= url('login'); ?>"
                            class="text-white hover:underline">بازگشت به صفحه ورود</a></p>
                </div>

            </div>
        </div>
    </div>



@endsection