<?php if (empty($comments)): ?>
<tr>
    <td colspan="6" class="text-center py-10 text-base-content/50">
        هیچ کامنتی ثبت نشده است
    </td>
</tr>
<?php endif; ?>
<style>
    table td {
        vertical-align: baseline !important;
    }
</style>
<?php foreach ($comments as $comment): ?>
<?php    $user = $comment->user(); ?>

<tr id="comment-row-<?= $comment->id ?>" class="<?= $comment->approved == 0 ? 'bg-slate-50' : '' ?>">
    <td class="w-px whitespace-nowrap"><?= $comment->id ?></td>

    <td>
        <div class="flex items-center gap-3">
            <div class="avatar">
                <div class="bg-base-content/10 h-10 w-10 rounded-full">
                    <img src="<?= isset($user->avatar) ? asset($user->avatar) : ''; ?>" alt="avatar">
                </div>
            </div>
            <div>
                <div class="font-medium">
                    <?= isset($user->first_name) ? $user->first_name . ' ' . $user->last_name : 'کاربر حذف شده'; ?>
                </div>
            </div>
        </div>
    </td>

    <td class="!max-w-[300px] !break-words !whitespace-normal !min-w-[200px]">
        <div>
            <span class="!text-sm comment-text-<?= $comment->id ?>">
                <?= mb_substr($comment->comment, 0, 300, 'UTF-8'); ?>
            </span>
            <?php
    $parentComment = \App\Comment::find($comment->parent_id);
    if ($parentComment != null) {
        echo "<span class='text-xs text-blue-400'>پاسخ به کامنت " .
            mb_substr(strip_tags($parentComment->comment), 0, 15, 'UTF-8') .
            "... با آیدی " . $parentComment->id . "</span>";
        }
            ?>
        </div>
    </td>

    <td class="text-center">
        <?php    if ($comment->approved == 1): ?>
        <span class="badge badge-soft badge-success badge-sm">تایید شده</span>
        <?php    else: ?>
        <span class="badge badge-soft badge-warning badge-sm">در انتظار تایید</span>
        <?php    endif; ?>
    </td>

    <td class="text-center">
        <?= jdate('H:m | d F Y', strtotime($comment->created_at)); ?>
    </td>

    <td class="text-left">
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
</tr>

<tr id="editor-row-<?= $comment->id ?>" class="comment-editor-row">
    <td colspan="6" class="bg-slate-50 p-4">
        <div class="flex flex-col gap-3">
            <span class="editor-mode-label-<?= $comment->id ?> text-sm font-semibold"></span>

            <div class="bg-white rounded-lg overflow-hidden shadow-sm">
                <textarea id="tinymce-comment-<?= $comment->id ?>" class="tinymce-comment-editor"></textarea>
            </div>

            <div class="flex gap-2 justify-end">
                <button type="button" class="btn btn-soft btn-secondary btn-sm btn-cancel-editor"
                    data-id="<?= $comment->id ?>">
                    انصراف
                </button>
                <button type="button" class="btn btn-primary btn-sm btn-save-editor" data-id="<?= $comment->id ?>">
                    <span class="editor-save-label-<?= $comment->id ?>"></span>
                </button>
            </div>
        </div>
    </td>
</tr>
<?php endforeach; ?>