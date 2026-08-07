@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | دسته بندی</title>
    <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?= asset('datatables.net/js/dataTables.min.js'); ?>"></script>
    <script src="<?= asset('flyonui/flyonui.js'); ?>"></script>
    <script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>

    <?php  require_once Config::get('app.BASE_DIR') . '/public/jdf/jdf.php';
                                 ?>
@endsection

@section('content')
    <div class="w-full p-2 flex items-center content-between justify-between space-y-2">
        <h1 class="text-3xl">قالب ایمیل ریست رمز عبور</h1>

        <ul class="menu lg:menu-horizontal">
            <li>
                <a href="<?= route('admin.setting.email.template', ['type' => 'resset_password']) ?>"
                    class="btn btn-soft btn-primary btn-sm">
                    قالب ایمیل ریست رمز عبور
                </a>
            </li>
        </ul>
    </div>
    <div class="w-full bg-slate-50 p-3">
        <textarea id="mm-tinymce-editor" name="bio">
                    <?= $mail_template[0]->html ?>
                                                                                                                </textarea>
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
                valid_elements: '*[*]',
                extended_valid_elements:
                    'style[type],link[href|rel|type],meta[name|content|charset],html,head,body',
                verify_html: false,
                cleanup: false,
                forced_root_block: false,
                entity_encoding: 'raw',

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