@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | نظرات</title>
    <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?= asset('datatables.net/js/dataTables.min.js'); ?>"></script>
    <script src="<?= asset('flyonui/flyonui.js'); ?>"></script>
    <script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>
    <style>
        .dt-search {
            display: none !important;
        }

        .dt-layout-start {
            display: none !important;
        }

        .dt-layout-end {
            display: none !important;
        }

        .comment-editor-row {
            display: none;
        }

        .comment-editor-row.open {
            display: table-row;
        }
    </style>
    <?php  require_once Config::get('app.BASE_DIR') . '/public/jdf/jdf.php';
     ?>
@endsection

@section('content')

    <div class="overflow-x-auto w-full">

        <div class="bg-base-100 flex flex-col rounded-md shadow-base-300/20 shadow-sm">

            <div class="flex flex-col gap-2 border-b border-base-300/50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="input input-sm w-full sm:max-w-80">
                    <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                    <label class="sr-only" for="comment-search">جست‌وجو در نظرات</label>
                    <input type="search" class="grow" id="comment-search" maxlength="100" autocomplete="off"
                        placeholder="جست‌وجو در متن نظرات...">
                    <span id="comment-search-loading" class="loading loading-spinner loading-xs hidden" aria-hidden="true"></span>
                </div>
                <span id="comment-search-status" class="text-sm text-base-content/60" role="status" aria-live="polite"></span>
            </div>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="table table-striped table-auto">
                            <thead class="bg-white">
                                <tr class="border-0 bg-base-300/20 *:first:rounded-s-md *:last:rounded-e-md">
                                    <th scope="col" class="w-px whitespace-nowrap">آیدی</th>
                                    <th scope="col" class="w-fit">کاربر</th>
                                    <th scope="col" class="w-full">کامنت</th>
                                    <th scope="col" class="w-fit text-center">وضعیت</th>
                                    <th scope="col" class="w-fit text-center">تاریخ</th>
                                    <th scope="col" class="w-fit text-left">عملیات</th>
                                </tr>
                            </thead>
                            <tbody id="comments-body" aria-busy="false">
                                @include('admin.comments.partials.rows')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        $(document).ready(function () {

            // آرایه‌ای برای نگهداری instance های تینی‌ام‌سی به ازای هر کامنت
            const initializedEditors = {};
            // نگهداری حالت فعلی هر ادیتور: 'edit' یا 'answer'
            const editorModes = {};

            const UPDATE_URL = "<?= url('admin/comments/update') ?>";
            const ANSWER_URL = "<?= url('admin/comments/answer') ?>";
            const SEARCH_URL = "<?= route('admin.comments.search') ?>";
            let searchTimer = null;
            let searchRequest = null;
            let searchVersion = 0;

            function setSearchLoading(isLoading) {
                $('#comment-search-loading').toggleClass('hidden', !isLoading);
                $('#comments-body').attr('aria-busy', isLoading ? 'true' : 'false');
            }

            function destroyCommentEditors() {
                Object.keys(initializedEditors).forEach(function (id) {
                    const editor = initializedEditors[id];

                    try {
                        if (editor && typeof editor.remove === 'function') {
                            editor.remove();
                        }
                    } catch (_error) {
                        // اگر ادیتور قبلاً از DOM حذف شده باشد، فقط state محلی پاک می‌شود.
                    }

                    delete initializedEditors[id];
                    delete editorModes[id];
                });
            }

            function replaceCommentRows(html) {
                destroyCommentEditors();
                $('#comments-body').html(html);
            }

            function loadComments(query) {
                const requestId = ++searchVersion;

                if (searchRequest) {
                    searchRequest.abort();
                }

                setSearchLoading(true);
                $('#comment-search-status').text('در حال جست‌وجو...');

                searchRequest = $.ajax({
                    url: SEARCH_URL,
                    type: 'GET',
                    dataType: 'json',
                    data: { q: query }
                })
                    .done(function (response) {
                        if (requestId !== searchVersion) return;

                        replaceCommentRows(response.html);

                        if (query === '') {
                            $('#comment-search-status').text('');
                        } else if (response.count === 0) {
                            $('#comment-search-status').text('نتیجه‌ای یافت نشد.');
                        } else if (response.limited) {
                            $('#comment-search-status').text('۵۰ نتیجهٔ اول نمایش داده شد.');
                        } else {
                            $('#comment-search-status').text(response.count + ' نتیجه پیدا شد.');
                        }
                    })
                    .fail(function (_xhr, status) {
                        if (status === 'abort' || requestId !== searchVersion) return;

                        $('#comment-search-status').text('خطا در جست‌وجو؛ دوباره تلاش کنید.');
                    })
                    .always(function () {
                        if (requestId !== searchVersion) return;

                        searchRequest = null;
                        setSearchLoading(false);
                    });
            }

            $('#comment-search').on('input', function () {
                const query = $(this).val().trim();

                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    loadComments(query);
                }, 350);
            });

            function initTinyMCE(id) {
                if (initializedEditors[id]) return;

                tinymce.init({
                    selector: '#tinymce-comment-' + id,
                    license_key: 'gpl',
                    promotion: false,
                    language: 'fa',
                    height: 250,
                    directionality: 'rtl',
                    toolbar_mode: 'sliding',
                    plugins: ['advlist', 'autolink', 'lists', 'link', 'charmap', 'searchreplace', 'visualblocks', 'wordcount'],
                    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link',
                    content_style: `
                                    body {
                                        font-family: vazir, Arial, sans-serif;
                                        direction: rtl;
                                        text-align: right;
                                        font-size: 14px;
                                        line-height: 1.8;
                                        padding: 12px;
                                    }
                                `,
                    setup: function (editor) {
                        editor.on('init', function () {
                            initializedEditors[id] = editor;
                        });
                    }
                });
            }

            function openEditor(id, mode) {
                // بستن سایر ادیتورهای بازشده
                $('.comment-editor-row.open').each(function () {
                    const openId = $(this).attr('id').replace('editor-row-', '');
                    if (openId != id) {
                        closeEditor(openId);
                    }
                });

                editorModes[id] = mode;

                const row = $('#editor-row-' + id);
                row.addClass('open');

                if (mode === 'edit') {
                    $('.editor-mode-label-' + id).text('ویرایش کامنت');
                    $('.editor-save-label-' + id).text('ذخیره');
                } else {
                    $('.editor-mode-label-' + id).text('پاسخ به کامنت');
                    $('.editor-save-label-' + id).text('ارسال پاسخ');
                }

                if (!initializedEditors[id]) {
                    initTinyMCE(id);
                    // منتظر می‌مانیم تا ادیتور init شود، سپس محتوا را ست می‌کنیم
                    const waitInit = setInterval(function () {
                        if (initializedEditors[id]) {
                            clearInterval(waitInit);
                            setEditorContent(id, mode);
                        }
                    }, 100);
                } else {
                    setEditorContent(id, mode);
                }
            }

            function setEditorContent(id, mode) {
                const editor = initializedEditors[id];
                if (!editor) return;

                if (mode === 'edit') {
                    // متن فعلی کامنت را در ادیتور قرار بده
                    const currentText = $('.comment-text-' + id).html();
                    editor.setContent(currentText);
                } else {
                    // برای پاسخ، ادیتور خالی باز شود
                    editor.setContent('');
                }
            }

            function closeEditor(id) {
                $('#editor-row-' + id).removeClass('open');
            }

            // ===== کلیک روی دکمه ویرایش =====
            $(document).on('click', '.btn-edit-comment', function () {
                const id = $(this).data('id');
                openEditor(id, 'edit');
            });

            // ===== کلیک روی دکمه پاسخ =====
            $(document).on('click', '.btn-answer-comment', function () {
                const id = $(this).data('id');
                openEditor(id, 'answer');
            });

            // ===== کلیک روی دکمه انصراف =====
            $(document).on('click', '.btn-cancel-editor', function () {
                const id = $(this).data('id');
                closeEditor(id);
            });

            // ===== کلیک روی دکمه ذخیره/ارسال =====
            $(document).on('click', '.btn-save-editor', function () {
                const id = $(this).data('id');
                const mode = editorModes[id];
                const editor = initializedEditors[id];

                if (!editor) return;

                const content = editor.getContent();
                const $btn = $(this);
                const originalText = $btn.find('.editor-save-label-' + id).text();

                $btn.prop('disabled', true);
                $btn.find('.editor-save-label-' + id).text('در حال ارسال...');

                const url = (mode === 'edit') ? UPDATE_URL : ANSWER_URL;
                const payload = {
                    id: id,
                    comment: content
                };

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: payload,
                    success: function (response) {
                        $btn.prop('disabled', false);
                        $btn.find('.editor-save-label-' + id).text(originalText);

                        if (mode === 'edit') {
                            // آپدیت فوری متن نمایش داده‌شده در جدول
                            $('.comment-text-' + id).html(content);
                        } else {
                            // برای پاسخ می‌توانید در صورت نیاز پیام موفقیت نشان دهید
                            editor.setContent('یریر');
                        }

                        closeEditor(id);
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false);
                        $btn.find('.editor-save-label-' + id).text(originalText);
                        alert('خطا در ارسال اطلاعات. لطفاً دوباره تلاش کنید.');
                    }
                });
            });

            // ===== حذف کامنت =====
            $(document).on('click', '.btn-delete-comment', function () {
                const id = $(this).data('id');
                if (!confirm('آیا از حذف این کامنت مطمئن هستید؟')) return;

                $.ajax({
                    url: "<?= url('admin/comments/delete') ?>/" + id,
                    type: 'POST',
                    data: { _method: 'delete' },
                    success: function () {
                        if (initializedEditors[id]) {
                            initializedEditors[id].remove();
                            delete initializedEditors[id];
                            delete editorModes[id];
                        }
                        $('#comment-row-' + id).fadeOut(300, function () {
                            $(this).remove();
                        });
                        $('#editor-row-' + id).remove();
                    },
                    error: function () {
                        alert('خطا در حذف کامنت');
                    }
                });
            });

            // ===== تایید / عدم تایید کامنت =====
            $(document).on('click', '.btn-toggle-approved', function () {
                const id = $(this).data('id');

                $.ajax({
                    url: "<?= url('admin/comments/app/') ?>/" + id,
                    type: 'POST',
                    data: { _method: 'put' },
                    success: function () {
                        location.reload();
                    },
                    error: function () {
                        alert('خطا در تغییر وضعیت تایید کامنت');
                    }
                });
            });

        });
    </script>

@endsection
