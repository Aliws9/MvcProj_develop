@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | دسته بندی</title>
    <script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>
    
@endsection

@section('content')


    <div class="w-full flex flex-col border border-1 border-amber-200 rounded-lg shadow">
        <div class="w-full">
            <h5 class="bg-base-300/10 rounded-t-lg p-4 text-xl font-bold">کاربر جدید</h5>
        </div>
        

        <form class="needs-validation peer grid gap-y-4 p-3" method="post" action="<?= route('admin.users.store') ?>">

            <div class="w-full flex flex-col md:flex-row gap-4">
                <div class="p-3 w-full md:w-2/5 gap-3 flex flex-col">
                    <div>
                        <label class="label-text" for="firstName">نام</label>
                        <input id="firstName" type="text" placeholder="علی" name="first_name" class="input" required />
                    </div>
                    <div>
                        <label class="label-text" for="lastName">نام خوانوادگی</label>
                        <input id="lastName" type="text" placeholder="اسدی" name="last_name" class="input" required />
                    </div>
                    <div>
                        <label class="label-text" for="lastName">نقش کاربر</label>
                        <select class="input" name="user_type">
                            <option value="admin2">ادمین دوم</option>
                            <option value="editor">ادیتور</option>
                            <option value="admin_seo">مدیر SEO</option>
                            <option value="normal" selected>کاربر معمولی</option>
                        </select>
                    </div>
                    <div class="flex flex-row gap-2 items-center">
                        <div class="w-1/2">
                            <div class="flex gap-2 items-center">
                                <input name="send_mail" type="checkbox" class="checkbox checkbox-primary mt-2" id="checkboxLabel1" />
                                <label class="label-text cursor-pointer flex flex-col" for="checkboxLabel1">
                                    <span>ارسال ایمیل بعد از ثبت.</span>
                                </label>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="flex items-center gap-1">
                                <input type="checkbox" class="switch switch-primary" name="is_active" id="switchType1" checked  />
                                <label class="label-text text-base" for="switchType1">کاربر فعال یا غیرفعال</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div data-mm-field="post_image" class="mm-field-wrapper w-full">
                            <input type="hidden" name="avatar" class="mm-hidden-input" value="<?= old('image') ?>">

                            <button type="button" class="btn btn-soft btn-primary w-full mm-trigger-btn"
                                data-mm-target="[data-mm-field='post_image']">
                                <span class="icon-[tabler--photo] size-4"></span>
                                تصویر پروفایل (اختیاری)
                            </button>

                            <div class="mm-preview mt-2">
                                <?php if (old('image')): ?>
                                <div class="relative inline-block">
                                    <img src="<?= old('image') ?>"
                                        class="w-full h-32 object-cover rounded-lg border-2 border-primary">
                                    <button type="button"
                                        class="mm-clear-btn absolute -top-2 -right-2
                                                                                                                       btn btn-circle btn-xs btn-error">
                                        <span class="icon-[tabler--x] size-3"></span>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="p-3 w-full md:w-3/5 gap-3 flex flex-col">
                    <div>
                        <label class="label-text" for="userEmail">ایمیل</label>
                        <input id="userEmail" type="email" name="email" class="input" placeholder="john@gmail.com"
                            aria-label="john@gmail.com" required="" />
                    </div>

                    <div class="flex md:flex-row flex-col gap-5">
                        <div class="md:w-1/2 w-full">
                            <label class="label-text" for="userEmail">(نام کاربری (یونیک</label>
                            <input id="username" type="text" name="username" class="input" placeholder="usern" required />
                        </div>
                        <div class="md:w-1/2 w-full">
                            <label class="label-text" for="userPassword">رمز عبور</label>
                            <div class="input">
                                <input id="userPassword" type="password" name="password" class="grow"
                                    placeholder="پسورد خودرا وارد کنید..." required />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <label class="label-text" for="userBio">Bio</label>
                        <textarea id="mm-tinymce-editor" name="bio">

                                                                                                </textarea>
                    </div>

                </div>

            </div>
            <div class="mt-4 p-2">
                <button type="submit" name="submitButton" class="btn btn-primary">ذخیره کاربر</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {

            // ===== راه‌اندازی TinyMCE با دکمه سفارشی =====
            tinymce.init({
                selector: '#mm-tinymce-editor',
                license_key: 'gpl',
                promotion: false,
                language: 'fa',
                height: '350px',
                directionality: 'rtl',
                toolbar_mode: 'sliding',

                plugins: [
                    'code', 'advlist', 'autolink', 'lists', 'link', 'charmap',
                    'preview', 'anchor', 'searchreplace', 'visualblocks',
                    'fullscreen', 'insertdatetime', 'media', 'table', 'wordcount'
                ],

                // *** toolbar با دکمه media سفارشی ***
                toolbar: 'undo redo | blocks | code | bold italic | alignleft aligncenter alignright '
                    + '| bullist numlist | link | mm_media | fullscreen',



                // *** تعریف دکمه سفارشی ***
                setup: function (editor) {
                    editor.ui.registry.addButton('mm_media', {
                        icon: 'image',
                        tooltip: 'درج رسانه',
                        onAction: function () {
                            // باز کردن مدیر رسانه در حالت tinymce
                            MediaManager.open({
                                mode: 'tinymce',
                                editor: editor
                            });
                        }
                    });
                },
                content_style: `
                                                                                        body {
                                                                                            font-family: vazir, Arial, sans-serif;
                                                                                            direction: rtl;
                                                                                            text-align: right;
                                                                                            font-size: 14px;
                                                                                            line-height: 1.8;
                                                                                            padding: 16px;
                                                                                        }
                                                                                    `
            });

            // این کد فقط یه بار در layout نوشته می‌شه
            $(document).on('click', '.mm-trigger-btn', function () {
                const target = $(this).data('mm-target');

                MediaManager.open({
                    mode: 'input',
                    target: target
                });
            });

        });
    </script>

@endsection