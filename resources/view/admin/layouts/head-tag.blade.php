<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?php echo asset('tailwind/output.css'); ?>">
<script src="<?= asset('flyonui/flyonui.js'); ?>">
</script>

<!-- Media Manager Scripts - یک بار در layout -->
<link rel="stylesheet" href="<?= asset('filepond/dist/filepond.min.css'); ?>">
<link rel="stylesheet" href="<?= asset('filepond/plugin/image_preview/dist/filepond-plugin-image-preview.min.css') ?>">

<script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
<script src="<?= asset('filepond/dist/filepond.min.js'); ?>"></script>
<script src="<?= asset('filepond/plugin/image_preview/dist/filepond-plugin-image-preview.min.js') ?>"></script>
<script>
    FilePond.registerPlugin(FilePondPluginImagePreview);
</script>
<script src="<?= asset('admin-assets/media-manager.js'); ?>"></script>
<script>
    // یک بار در کل سایت
    MediaManager.init({
        listUrl: "<?= route('admin.media.list') ?>",
        uploadUrl: "<?= route('admin.media.store') ?>",
        deleteUrl: "<?= url('admin/media/delete') ?>",
        altUrl: "<?= url('admin/media/altImage') ?>",
    });

    // clear button - هر جا استفاده شد
    $(document).on('click', '.mm-clear-btn', function () {
        const container = $(this).closest('[data-mm-field]');
        container.find('.mm-hidden-input').val('');
        container.find('.mm-preview').empty();
    });
</script>