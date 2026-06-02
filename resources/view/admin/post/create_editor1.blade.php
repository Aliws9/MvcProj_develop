@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ایجاد مقاله (ادیتور ساده)</title>
    <script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>
    <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
@endsection

@section('content')
    <style>
        .tox-statusbar__branding {
            display: none !important;
        }

        .tox {
            font-family: Tahoma, Arial, sans-serif !important;
        }

        .tox .tox-tbtn,
        .tox .tox-split-button,
        .tox .tox-dropdown__display {
            font-size: 14px !important;
        }


        .tox-menubar button span {
            font-size: 14px !important;
        }
    </style>

    <div class="form_cust w-full md:w-full m-auto">
        <h1 class="text-lg md:text-3xl mb-5">مقاله جدید</h1>
        <div class="w-full bg-slate-100 m-auto p-2 rounded-lg h-[90vh] lg:h-[100vh]">

            <form method="POST" action="" class="h-full">

                <textarea id="editor" name="content"></textarea>

            </form>

            <br>
        </div>
        <br>


        <script>
            $(document).ready(function () {
                tinymce.init({
                    selector: '#editor',
                    license_key: 'gpl',
                    promotion: false,
                    language: 'fa',
                    height: '100%',
                    directionality: 'rtl',
                    toolbar_mode: 'sliding',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                        'preview', 'anchor', 'searchreplace', 'visualblocks',
                        'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | fullscreen',
                    content_style: `
                    body {
                      font-family: Tahoma, Arial, sans-serif;
                      direction: rtl;
                      text-align: right;
                    }
                  `
                });


                $("#send").click(function () {
                    var myContent = tinymce.get("editor").getContent();

                    $('#cont').text(myContent);
                });
            });

        </script>
        <button id="send" type="button" class="btn btn-primary">send</button>
        <div id="cont" class="p-2 bg-red-200 mt-4"></div>





    </div>


@endsection