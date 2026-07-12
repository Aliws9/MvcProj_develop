@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ایجاد مقاله</title>
    <script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>

    <link type="text/css" rel="stylesheet" href="<?= asset('jalalidatepicker/dist/jalalidatepicker.min.css') ?>" />
    <script type="text/javascript" src="<?= asset('jalalidatepicker/dist/jalalidatepicker.min.js') ?>"></script>
@endsection

@section('content')


    <div class="form_cust w-full">

        <div class="w-full md:w-3/4 m-auto">
            <!-- error name alert -->
            <?php
    $errorTitle = errorClass('title');
    $errorBody = errorClass('body');
    $errorImage = errorClass('image');
    $errorCategory = errorClass('cat_id');
    $errorSeoDescription = errorClass('seo_description');
    $errorSeoTitle = errorClass('seo_title');
    $errorsummary = errorClass('summary');
    ?>
            <!-- title -->
            <div class="<?= $errorTitle['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-name-title">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorTitle['message_error'] ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-name-title"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <!-- body -->

            <div class="<?= $errorBody['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-name-body">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorBody['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-name-body"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <!-- seo_description -->

            <div class="<?= $errorSeoDescription['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-name-seo-description">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorSeoDescription['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none"
                    data-remove-element="#dismiss-alert-error-name-seo-description" aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <!-- seo_title -->

            <div class="<?= $errorSeoTitle['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-seo-title">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorSeoTitle['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-seo-title"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <!-- summary -->

            <div class="<?= $errorsummary['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-seo-summary">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorsummary['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-seo-summary"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <!-- image -->

            <div class="<?= $errorImage['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-name-image">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorImage['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-name-image"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>
        </div>



        <h1 class="text-lg md:text-2xl mb-5 font-semibold">ویرایش مقاله</h1>
        <form method="post" action="<?= route('admin.post.update', [$post->id]) ?>" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="put">
            <input type="hidden" name="id" value="<?= $post->id ?>">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- ستون اصلی: ویرایشگر -->
                <div class="lg:col-span-2 flex flex-col gap-4">

                    <!-- عنوان -->
                    <div class="input-floating">
                        <input type="text" name="title" placeholder="عنوان مقاله" class="input !border-blue-400"
                            value="<?= $post->title; ?>">
                        <label class="input-floating-label">عنوان مقاله</label>
                    </div>

                    <!-- ویرایشگر TinyMCE -->
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm h-full min-h-[350px] md:min-h-[500px]">
                        <textarea id="mm-tinymce-editor" name="body">
                                                                    <?= $post->body ?>
                                                                </textarea>
                    </div>

                    <!-- seo title -->
                    <div>
                        <div class="space-x-2 !flex !items-center">
                            <label>عنوان در گوگل</label>
                            <span class="text-xs badge badge-soft badge-success badge-sm">50 تا 60 کاراکتر</span>
                            <div class="input-wrapper">
                                <span class="badge badge-soft badge-info text-xs char-count">0 کاراکتر</span>
                            </div>
                        </div>
                        <input type="text" placeholder="تایتل مقاله (مقدار پیشفرض)" class="input seo-input"
                            name="seo_title" value="<?= $post->seo_title ?>">
                    </div>

                    <!-- seo description -->
                    <div>
                        <div class="space-x-2 !flex !items-center">
                            <label>توصیحات در گوگل</label>
                            <span class="text-xs badge badge-soft badge-success badge-sm">140 تا 160 کاراکتر</span>
                            <div class="input-wrapper">
                                <span class="badge badge-soft badge-info text-xs char-count">0 کاراکتر</span>
                            </div>
                        </div>
                        <textarea placeholder="180 کاراکتر از ابتدای پاراگراف (مقدار پیشفرض)"
                            class="input !h-[700px] md:!h-[70px] py-2 seo-input"
                            name="seo_description"><?= $post->seo_description ?></textarea>
                        <!-- <label>کلمات کلیدی</label>
                                            <input type="text" class=""> -->
                    </div>

                    <!-- summary -->
                    <div>
                        <div class="space-x-2 !flex !items-center">
                            <label>خلاصه</label>
                            <span class="text-xs badge badge-soft badge-success badge-sm">حداکثر 300 کاراکتر</span>
                            <div class="input-wrapper">
                                <span class="badge badge-soft badge-info text-xs char-count">0 کاراکتر</span>
                            </div>
                        </div>
                        <textarea placeholder="300 کاراکتر از ابتدای پاراگراف (مقدار پیشفرض)"
                            class="input !h-[100px] md:!h-[100px] py-2 seo-input"
                            name="summary"><?= $post->summary ?></textarea>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $('.seo-input').each(function () {
                                let input = $(this);
                                let counter = input.closest('div').find('.char-count');

                                input.on('input', function () {
                                    counter.text(input.val().length);
                                });
                            });
                        });
                    </script>

                </div>

                <!-- ستون کناری: تنظیمات -->
                <div class="flex flex-col gap-4">

                    <!-- دکمه انتشار -->
                    <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col gap-3">
                        <h3 class="font-semibold">بروزرسانی</h3>
                        <button type="submit" class="btn btn-primary btn-block">
                            <span class="icon-[tabler--send] size-5"></span>
                            بروزرسانی مقاله
                        </button>
                    </div>

                    <!-- دسته‌بندی -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="font-semibold mb-3">دسته‌بندی</h3>
                        <select name="cat_id" class="select">
                            <option selected value="<?= !isset($post->category()->id) ? null : $post->category()->id; ?>"
                                class="text-xs lg:text-md">
                                <?= !isset($post->category()->id) ? 'دسته بندی والد' : $post->category()->name; ?></option>
                            <?php echo buildCategoryTree($categories); ?>

                        </select>
                    </div>

                    <!-- تصویر شاخص -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="font-semibold mb-3">تصویر شاخص</h3>

                        <div data-mm-field="post_image" class="mm-field-wrapper w-full">
                            <input type="hidden" name="image" class="mm-hidden-input" value="<?= $post->image; ?>">

                            <button type="button" class="btn btn-soft btn-primary btn-sm w-full mm-trigger-btn"
                                data-mm-target="[data-mm-field='post_image']">
                                <span class="icon-[tabler--photo] size-4"></span>
                                انتخاب تصویر شاخص
                            </button>

                            <div class="mm-preview mt-2">

                                <div class="relative inline-block">
                                    <img src="<?= $post->image ?>"
                                        class="w-full h-32 object-cover rounded-lg border-2 border-primary">
                                    <button type="button" class="mm-clear-btn absolute -top-2 -right-2
                                                                                       btn btn-circle btn-xs btn-error">
                                        <span class="icon-[tabler--x] size-3"></span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>
    <br>
    <br>
    <br>

    <script>
        jalaliDatepicker.startWatch({
            time: true,
        });

    </script>
    <style>
        .overlay {
            pointer-events: all;
        }

        .disabled-box.disabled .content {
            pointer-events: none;
            opacity: 0.35;
        }
    </style>
    <script>
        $(document).ready(function () {

            function disableBox(selector) {
                const box = $(selector);
                box.addClass('disabled');
                box.find('.overlay').removeClass('hidden');
                box.find('input, textarea, button, select').prop('disabled', true);
            }

            function enableBox(selector) {
                const box = $(selector);
                box.removeClass('disabled');
                box.find('.overlay').addClass('hidden');
                box.find('input, textarea, button, select').prop('disabled', false);
            }

            disableBox('.disabled-box');

            $('.publish_date').click(function () {
                enableBox('.disabled-box');
            });

            $('.publish_now').click(function () {
                disableBox('.disabled-box');
                $('#flatpickr-date-time2').val('');
            });

            // این کد فقط یه بار در layout نوشته می‌شه
            $(document).on('click', '.mm-trigger-btn', function () {
                const target = $(this).data('mm-target');

                MediaManager.open({
                    mode: 'input',
                    target: target
                });
            });


            // ===== راه‌اندازی TinyMCE با دکمه سفارشی =====
            tinymce.init({
                selector: '#mm-tinymce-editor',
                license_key: 'gpl',
                promotion: false,
                language: 'fa',
                height: '100%',
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

        });
    </script>

    <style>
        .tox-dialog textarea {
            direction: ltr !important;
            text-align: left !important;
            font-family: monospace !important;
        }

        .tox,
        .tox-tinymce {
            font-family: vazir !important;
        }
    </style>


@endsection