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
@endsection

@section('content')

    <div class="overflow-x-auto w-full">

        <div class="bg-base-100 flex flex-col rounded-md shadow-base-300/20 shadow-sm">

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="table table-striped">
                            <thead class="bg-white">
                                <tr class="border-0 bg-base-300/20 *:first:rounded-s-md *:last:rounded-e-md">
                                    <th scope="col" class="w-fit">کاربر</th>
                                    <th scope="col" class="w-fit">کامنت</th>
                                    <th scope="col" class="w-fit">وضعیت</th>
                                    <th scope="col">عملیات</th>
                                    <th scope="col">آیدی کامنت</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                    // use App\Comment;

 if (empty($comments)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-base-content/50">
                                        هیچ کامنتی ثبت نشده است
                                    </td>
                                </tr>
                                <?php endif; ?>

                                <?php foreach ($comments as $comment): ?>
                                <?php $user = $comment->user(); ?>

                                <tr id="comment-row-<?= $comment->id ?>">

                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar">
                                                <div class="bg-base-content/10 h-10 w-10 rounded-full">
                                                    <img src="<?= isset($user->avatar) ? asset($user->avatar) : ''; ?>"
                                                        alt="avatar">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-medium">
                                                    <?= isset($user->first_name) ? $user->first_name . ' ' . $user->last_name : 'کاربر حذف شده'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="!max-w-[300px] !break-words !whitespace-normal !min-w-[200px] comment-text-<?= $comment->id ?>">
                                        <div>
                                            <span class="!text-sm">
                                        <?= $comment->comment; ?>
                                        </span>
                                        <?php $rr = Comment::find($comment->parent_id);
                                        if($rr != null){
                                            echo "                                        <span class='text-xs text-blue-400'>پاسخ به کامنت ". mb_substr(strip_tags($rr->comment),0,15,'UTF-8') ."... با آیدی ". $comment->id ."</span>
";
                                        }
                                          ?>

                                        </div>

                                    </td>

                                    <td>
                                        <?php if ($comment->approved == 1): ?>
                                            <span class="badge badge-soft badge-success badge-sm">تایید شده</span>
                                        <?php else: ?>
                                            <span class="badge badge-soft badge-warning badge-sm">در انتظار تایید</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <!-- <form action="<?php // echo route('admin.comments.destroy', [$comment->id]) ?>"
                                                    method="post" class="inline ajax-form join-item">
                                                    <input type="hidden" name="_method" value="delete">
                                                    <button type="submit">
                                            <span class="badge badge-soft badge-error badge-sm cursor-pointer btn-delete-comment"
                                            data-id="<?php //echo $comment->id ?>">حذف</span>
                                            </button>
                                            </form> -->
                                        <span class="badge badge-soft badge-error badge-sm cursor-pointer btn-delete-comment"
                                            data-id="<?= $comment->id ?>">حذف</span>

                                        <span class="badge badge-soft badge-success badge-sm cursor-pointer btn-toggle-approved"
                                            data-id="<?= $comment->id ?>">
                                            <?= $comment->approved == 1 ? 'عدم تایید' : 'تایید'; ?>
                                        </span>

                                        <span class="badge badge-soft badge-info badge-sm cursor-pointer btn-answer-comment"
                                            data-id="<?= $comment->id ?>">پاسخ</span>

                                        <span class="badge badge-soft badge-primary badge-sm cursor-pointer btn-edit-comment"
                                            data-id="<?= $comment->id ?>">ویرایش</span>
                                    </td>

                                    <td><?= $comment->id ?></td>

                                </tr>

                                <!-- ردیف ادیتور inline (accordion) - برای ویرایش یا پاسخ -->
                                <tr id="editor-row-<?= $comment->id ?>" class="comment-editor-row">
                                    <td colspan="4" class="bg-slate-50 p-4">
                                        <div class="flex flex-col gap-3">
                                            <span class="editor-mode-label-<?= $comment->id ?> text-sm font-semibold"></span>

                                            <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                                                <textarea id="tinymce-comment-<?= $comment->id ?>"
                                                    class="tinymce-comment-editor"></textarea>
                                            </div>

                                            <div class="flex gap-2 justify-end">
                                                <button type="button"
                                                    class="btn btn-soft btn-secondary btn-sm btn-cancel-editor"
                                                    data-id="<?= $comment->id ?>">
                                                    انصراف
                                                </button>
                                                <button type="button"
                                                    class="btn btn-primary btn-sm btn-save-editor"
                                                    data-id="<?= $comment->id ?>">
                                                    <span class="editor-save-label-<?= $comment->id ?>"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <?php endforeach; ?>
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
                    url: "<?= url('admin/comments/app') ?>",
                    type: 'POST',
                    data: { _method: 'put', id: id },
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