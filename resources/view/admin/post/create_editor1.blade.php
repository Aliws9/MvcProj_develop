@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ایجاد مقاله (ادیتور ساده)</title>
    <script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>
    <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>

    <link rel="stylesheet" href="<?= asset('filepond/dist/filepond.min.css'); ?>">
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
        <div class="w-full bg-slate-100 m-auto p-2 rounded-lg h-[90vh] lg:h-[100vh] flex flex-col gap-7">


            <!-- Middle Center -->

            <button type="button" class="btn btn-primary w-fit" aria-haspopup="dialog" aria-expanded="false"
                aria-controls="middle-center-modal" data-overlay="#middle-center-modal">درج رسانه</button>

            <div id="middle-center-modal"
                class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 overlay-backdrop-open:bg-primary/30 modal-middle hidden [--body-scroll:true]"
                role="dialog" tabindex="-1">
                <div class="modal-dialog lg:modal-dialog-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">مدیریت رسانه ها</h3>
                            <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                                aria-label="Close" data-overlay="#middle-center-modal">
                                <span class="icon-[tabler--x] size-4"></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="file" id="filepond" name="filepond" multiple>

                            <input type="hidden" id="uploaded_files" name="uploaded_files"><br>
                            <div id="media-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3"></div>


                        </div>
                        <div class="modal-footer">
                            <input type="button" value="آپلود" class="btn btn-primary btn-soft" id="send_media">
                        </div>
                    </div>
                </div>
            </div>

<script>
$(document).ready(function () {
    const pond = FilePond.create(document.querySelector('#filepond'), {
        credits: false,
        allowMultiple: true,
        maxFiles: 30,
        instantUpload: false,   // آپلود خودکار خاموش
        storeAsFile: false,

        server: {
            process: {
                url: "{{ route('admin.media.store') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                onload: (response) => response
            }
        }
    });

    $('#send_media').on('click', function () {
        // شروع آپلود همه فایل‌های صف
        pond.processFiles();
    });

    pond.on('processfile', function (error, file) {
        if (error) {
            console.error(error);
            return;
        }

        // اینجا بعد از آپلود کامل هر فایل اجرا می‌شود
        const serverPath = file.serverId;
        console.log('Uploaded:', serverPath);

        // نمونه: افزودن به hidden input
        let current = $('#uploaded_files').val();
        let arr = current ? JSON.parse(current) : [];
        arr.push(serverPath);
        $('#uploaded_files').val(JSON.stringify(arr));

        // اینجا می‌توانی preview بسازی یا هر کار دیگری انجام بدهی
    });
});
</script>
            <!-- Middle End -->


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
                    $('#cont2').html(myContent);
                });
            });

        </script>
        <button id="send" type="button" class="btn btn-primary">send</button>
        <div id="cont" class="p-2 bg-red-200 mt-4"></div><br>
        <div id="cont2" class="p-2 bg-red-200 mt-4"></div>





    </div>
    <script src="<?= asset('filepond/dist/filepond.min.js') ?>"></script>

@endsection