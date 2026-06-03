@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ایجاد مقاله (ادیتور ساده)</title>
    <script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>
    <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
<link
    href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css"
    rel="stylesheet"
/>

<!-- add before </body> -->
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
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
                <div class="modal-dialog modal-dialog-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                                aria-label="Close" data-overlay="#middle-center-modal">
                                <span class="icon-[tabler--x] size-4"></span>
                            </button>

                        </div>

                        <div class="modal-body">
                            <nav class="tabs tabs-bordered  justify-center" aria-label="Tabs" role="tablist"
                                aria-orientation="horizontal">
                                <button type="button" class="tab active-tab:tab-active active" id="tabs-center-item-1"
                                    data-tab="#tabs-center-1" aria-controls="tabs-center-1" role="tab" aria-selected="true">
                                    همه رسانه ها
                                </button>
                                <button type="button" class="tab active-tab:tab-active" id="tabs-center-item-2"
                                    data-tab="#tabs-center-2" aria-controls="tabs-center-2" role="tab"
                                    aria-selected="false">
                                    آپلود جدید
                                </button>
                            </nav>
                            <div class="mt-3">
                                <div id="tabs-center-1" role="tabpanel" aria-labelledby="tabs-center-item-1"
                                    class="flex flex-col gap-5">

                                    <div class="flex flex-row gap-2 flex-row-reverse">
                                        <!-- sidebar -->
                                        <div class="bg-slate-100 border-1 border-slate-200 h-full p-2 w-2/7">
                                            side
                                        </div>

                                        <!-- content media -->
                                        <div class="bg-slate-100 border-1 border-slate-200 p-2 w-5/7">
                                            image
                                        </div>

                                    </div>

                                    <input type="button" value="درج" class="btn btn-primary btn-soft btn-disabled w-fit"
                                        id="merge_media2">

                                </div>

                                <div id="tabs-center-2" class="hidden" role="tabpanel" aria-labelledby="tabs-center-item-2">


                                    <input type="file" id="filepond" name="filepond" multiple>

                                    <input type="hidden" id="uploaded_files" name="uploaded_files"><br>
                                    <div id="media-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3"></div>

                                    <input type="button" value="آپلود" class="btn btn-primary btn-soft" id="send_media">
                                    <input type="button" value="درج" class="btn btn-primary btn-soft btn-disabled"
                                        id="merge_media">


                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
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
                        instantUpload: false,
                        storeAsFile: false,

                        server: {
                            process: {
                                url: "<?= route('admin.media.store') ?>",
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                onload: (response) => response
                            }
                        }
                    });

                    $('#send_media').on('click', function () {
                        pond.processFiles();
                    });

                    $(document).on('click', '.edit-media', function(){

    let path = $(this)
        .closest('.media-item')
        .data('path');

    console.log(path);

    // باز کردن سایدبار اطلاعات
});

$(document).on('click', '.delete-media', function(){

    let card = $(this).closest('.media-item');

    let path = card.data('path');

    let files = JSON.parse($('#uploaded_files').val() || '[]');

    files = files.filter(item => item !== path);

    $('#uploaded_files').val(JSON.stringify(files));

    card.remove();
});

                    pond.on('processfile', function (error, file) {

    if (error) {
        console.error(error);
        return;
    }

    const serverPath = file.serverId;

    let current = $('#uploaded_files').val();
    let arr = current ? JSON.parse(current) : [];
    arr.push(serverPath);
    $('#uploaded_files').val(JSON.stringify(arr));

    // ----------------------
    // نمایش فایل آپلود شده
    // ----------------------

    let ext = serverPath.split('.').pop().toLowerCase();

    let preview = '';

    if (['jpg','jpeg','png','webp','gif','bmp'].includes(ext)) {

        preview = `
            <img src="${serverPath}"
                 class="w-full h-40 object-cover rounded">
        `;

    } else if (['mp4','webm','mov'].includes(ext)) {

        preview = `
            <video controls class="w-full rounded">
                <source src="${serverPath}">
            </video>
        `;

    } else if (['mp3','wav','ogg'].includes(ext)) {

        preview = `
            <audio controls class="w-full">
                <source src="${serverPath}">
            </audio>
        `;

    } else {

        preview = `
            <div class="p-3 text-center">
                <span>${file.filename}</span>
            </div>
        `;
    }

    $('#media-preview').prepend(`
        <div class="media-item border rounded p-2 bg-white"
             data-path="${serverPath}">

            ${preview}

            <div class="flex gap-2 mt-2">

                <button type="button"
                        class="edit-media btn btn-sm btn-soft">
                    ویرایش
                </button>

                <button type="button"
                        class="delete-media btn btn-sm btn-error">
                    حذف
                </button>

            </div>

        </div>
    `);

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