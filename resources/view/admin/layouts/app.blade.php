<!DOCTYPE html>
<html lang="fa" dir="rtl" data-theme="light" class="bg-slate-100">
<?php use System\Config\Config; ?>

<head>
    @include('admin.layouts.head-tag')
    @yield('head-tag')
    <script src="<?= asset('flyonui/flyonui.js'); ?>">
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