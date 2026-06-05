<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="light" class="bg-slate-100">
<?php use System\Config\Config; ?>

<head>
    @include('admin.layouts.head-tag')
    @yield('head-tag')
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
        listUrl:   "<?= route('admin.media.list') ?>",
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

</head>

<body class="h-full">
    <style>
        li ul {
            margin-top: 3px !important;
            margin-bottom: 3px !important;
        }
    </style>
    <!-- Main Content -->
    <div class="containers flex w-full lg:p-10 gap-6">

        <div class="md:w-2/10 hidden md:block">
            <!-- sidebar -->
            @include('admin.layouts.sidebar')

        </div>

        <div class="flex flex-col items-start w-full lg:w-8/10 gap-7 p-2.5 lg:p-0">

            <!-- Top Header -->
            @include('admin.layouts.header')

            <div class="w-full p-2.5 lg:p-8 items-start bg-white rounded-lg flex flex-col gap-5 shadow-lg">
                <!-- content -->

                <div class="w-full">
                    @yield('content')
                </div>

            </div>

        </div>
    </div>

    @include('admin.layouts.scripts')





</body>

</html>