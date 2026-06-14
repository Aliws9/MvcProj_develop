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
        <h1 class="text-lg md:text-2xl mb-5 font-semibold">ویرایش مقاله</h1>

        <form method="POST" action="<?= route('admin.post.update' , [$post->id]) ?>" enctype="multipart/form-data">

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

                    <div>
                    <label>توضیحات</label>
                    <textarea class="input !h-[100px] md:!h-[200px]" name="description"><?= $post->description ?></textarea>
                    <!-- <label>کلمات کلیدی</label>
                    <input type="text" class=""> -->
                    </div>

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
                            <option selected value="<?= $post->category()->id; ?>" class="text-xs lg:text-md"><?= $post->category()->name; ?></option>
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