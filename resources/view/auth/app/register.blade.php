@extends('admin.layouts.page')

<head>
    @section('head-tag')
        <?php use System\Config\Config; ?>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ثبت‌نام</title>
        <link rel="stylesheet" href="<?php echo asset('tailwind/output.css'); ?>">
        <script src="<?= asset('flyonui/flyonui.js'); ?>"></script>

        <!-- FilePond -->
        <link rel="stylesheet" href="<?= asset('filepond/dist/filepond.min.css'); ?>">
        <link rel="stylesheet"
            href="<?= asset('filepond/plugin/image_preview/dist/filepond-plugin-image-preview.min.css') ?>">

        <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
        <script src="<?= asset('filepond/dist/filepond.min.js'); ?>"></script>
        <script src="<?= asset('filepond/plugin/image_preview/dist/filepond-plugin-image-preview.min.js') ?>"></script>

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
                width: 400px;
                height: 150px;
                margin: 0 auto;
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

    <div class="relative w-full md:w-[70%] lg:w-2/6">
        <div class="bg-white/20 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">

                <div class="text-center mb-6">
                    <h1 class="text-3xl font-bold text-white mb-2">ایجاد حساب کاربری</h1>
                    <p class="text-white/80">برای ثبت‌نام اطلاعات زیر را تکمیل کنید</p>
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
    ; ?>

                <!-- پیام خطای عمومی برای ارسال AJAX -->
                <div id="ajaxErrorAlert" class="hidden alert alert-error alert-soft flex items-center gap-3 mb-3"
                    role="alert">
                    <span class="icon-[tabler--alert-triangle] shrink-0 size-5"></span>
                    <p class="text-sm" id="ajaxErrorMessage">خطایی رخ داد. دوباره تلاش کنید.</p>
                </div>

                <!-- فرم ثبت‌نام: تمام فیلدها هم‌نام با ستون‌های جدول users -->
                <form id="registerForm" novalidate>

                    <!-- تصویر پروفایل -->
                    <div class="mb-6">
                        <label class="block text-white/90 text-sm mb-2 text-center">تصویر پروفایل</label>

                        <div class="flex justify-center">
                            <div class="avatar-filepond-wrap">
                                <input type="file" id="avatarFilepond" accept="image/png,image/jpeg,image/jpg">
                            </div>
                        </div>

                        <!-- مسیر فایل آپلود شده که در سرور ذخیره شده -->
                        <input type="hidden" name="avatar" id="avatarPathInput" value="<?=  ''; ?>">

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

                    <!-- یوزر نیم -->
                    <div class="relative mb-4">
                        <input type="text" name="username" value="<?= old('username'); ?>"
                            class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 ps-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition"
                            placeholder="نام کاربری (به انگلیسی وارد کنید)" required>
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <span class="icon-[tabler--user] size-5 text-white/70"></span>
                        </div>
                    </div>

                    <!-- رمز عبور -->
                    <div class="relative mb-4">
                        <input type="password" name="password" id="passwordInput"
                            class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 ps-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition toggle-password-checkbox1"
                            placeholder="رمز عبور (حداقل ۸ کاراکتر)" minlength="8" required>
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <span class="icon-[tabler--lock] size-5 text-white/70"></span>
                        </div>

                    </div>

                    <!-- تکرار رمز عبور -->
                    <!-- password_confirmation -->
                    <div class="relative mb-6">
                        <input type="password" name="confirm_password" id="passwordConfirmInput"
                            class="w-full bg-white/20 text-white placeholder-white/50 rounded-lg py-3 px-4 ps-10 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/30 transition toggle-password-checkbox"
                            placeholder="تکرار رمز عبور" minlength="8" required>
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

                    <button type="submit" id="submitBtn"
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

    <!-- کادر (overlay) وضعیت آپلود/ارسال هنگام ثبت‌نام -->
    <div id="submitOverlay">
        <div class="bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 w-72">
            <span class="loading loading-spinner loading-lg text-primary"></span>
            <p class="text-center text-base-content font-medium" id="submitOverlayText">
                در حال آپلود تصویر و ثبت‌نام...
            </p>
        </div>
    </div>

    <script>
        (function () {
            FilePond.registerPlugin(FilePondPluginImagePreview);

            const avatarPathInput = document.getElementById('avatarPathInput');

            const pond = FilePond.create(document.querySelector('#avatarFilepond'), {
                credits: false,
                allowMultiple: false,
                maxFiles: 1,
                instantUpload: false, // <-- کلید حل مشکل: از آپلود خودکار جلوگیری می‌کند
                labelIdle: '<span class="icon-[tabler--camera-plus]" style="font-size:1.5rem;"></span><br>انتخاب تصویر',
                imagePreviewHeight: "115",

                server: {
                    process: {
                        url: "<?= route('auth.app.avatar.upload') ?>",
                        method: 'POST',
                        onload: (response) => {
                            avatarPathInput.value = response;
                            return response;
                        }
                    },
                    revert: (uniqueFileId, load, error) => {
                        fetch("<?= route('auth.app.avatar.delete') ?>", {
                            method: 'DELETE',
                            body: uniqueFileId
                        }).then(() => {
                            avatarPathInput.value = '';
                            load();
                        }).catch(() => error('خطا در حذف تصویر'));
                    }
                },
                // نمایش درصد آپلود روی overlay
                onprocessfileprogress: (file, progress) => {
                    overlayText.textContent = `در حال آپلود تصویر... ${Math.round(progress * 100)}%`;
                },
                <?php if (old('avatar')): ?>
                        files: [
                    {
                        source: "<?= old('avatar'); ?>",
                        options: {
                            type: 'local',
                            metadata: { poster: "<?= asset(old('avatar')); ?>" }
                        }
                    }
                ],
                <?php endif; ?>
            });

            // بعد از حذف تصویر توسط کاربر، مسیر ذخیره شده هم خالی می‌شود
            pond.on('removefile', function () {
                avatarPathInput.value = '';
            });

            // ===== ارسال فرم ثبت‌نام از طریق AJAX =====
            const form = document.getElementById('registerForm');
            const overlay = document.getElementById('submitOverlay');
            const overlayText = document.getElementById('submitOverlayText');
            const errorAlert = document.getElementById('ajaxErrorAlert');
            const errorMessage = document.getElementById('ajaxErrorMessage');
            const submitBtn = document.getElementById('submitBtn');

            function showOverlay(text) {
                overlayText.textContent = text;
                overlay.classList.add('active');
            }

            function hideOverlay() {
                overlay.classList.remove('active');
            }

            function showError(message) {
                errorMessage.textContent = message;
                errorAlert.classList.remove('hidden');
            }

            function hideError() {
                errorAlert.classList.add('hidden');
            }

            async function waitForUploadToFinish() {
                // اگر فایلی در حال آپلود بود صبر می‌کنیم تا تمام شود
                const files = pond.getFiles();
                const stillProcessing = files.some(f => !f.serverId && f.status !== FilePond.FileStatus.LOAD_ERROR);

                if (!stillProcessing) return true;

                showOverlay('در حال آپلود تصویر...');

                return new Promise((resolve) => {
                    const interval = setInterval(() => {
                        const currentFiles = pond.getFiles();
                        const allDone = currentFiles.every(f => f.serverId || f.status === FilePond.FileStatus.LOAD_ERROR);
                        if (allDone) {
                            clearInterval(interval);
                            resolve(currentFiles.every(f => f.serverId));
                        }
                    }, 200);
                });
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                hideError();

                const pass = document.getElementById('passwordInput').value;
                const confirmPass = document.getElementById('passwordConfirmInput').value;

                if (pass !== confirmPass) {
                    showError('رمز عبور و تکرار آن یکسان نیستند.');
                    return;
                }

                if (pond.getFiles().length > 0) {

                    submitBtn.disabled = true;
                    showOverlay('در حال آپلود تصویر... 0%');

                    pond.processFiles()
                        .then(() => {
                            if (!avatarPathInput.value) {
                                hideOverlay();
                                submitBtn.disabled = false;
                                showError('آپلود تصویر با خطا مواجه شد. دوباره تلاش کنید.');
                                return;
                            }

                            sendRegisterRequest();
                        })
                        .catch(() => {
                            hideOverlay();
                            submitBtn.disabled = false;
                            showError('آپلود تصویر با خطا مواجه شد. دوباره تلاش کنید.');
                        });

                } else {
                    sendRegisterRequest();
                }

                async function sendRegisterRequest() {
                    showOverlay('در حال ثبت‌نام...');
                    const formData = new FormData(form);

                    try {
                        const response = await fetch("<?= route('auth.app.register') ?>", {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            showOverlay('ثبت‌نام با موفقیت انجام شد. در حال انتقال...');
                            window.location.href = data.redirect || "<?= url('login') ?>";
                        } else {
                            hideOverlay();
                            submitBtn.disabled = false;
                            showError(data.message || 'ثبت‌نام با خطا مواجه شد.');
                        }
                    } catch (err) {
                        hideOverlay();
                        submitBtn.disabled = false;
                        showError('ارتباط با سرور برقرار نشد. دوباره تلاش کنید.');
                    }
                }
            });
        })();
    </script>
@endsection