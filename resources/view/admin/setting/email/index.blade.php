@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | دسته بندی</title>
    <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?= asset('datatables.net/js/dataTables.min.js'); ?>"></script>
    <script src="<?= asset('flyonui/flyonui.js'); ?>"></script>
    <?php  require_once Config::get('app.BASE_DIR') . '/public/jdf/jdf.php';
                 ?>
@endsection

@section('content')
    <div class="w-full p-2 flex items-center content-between justify-between space-y-2">
        <h1 class="text-3xl">تنظیمات</h1>

        <ul class="menu lg:menu-horizontal">
            <li>
                <a href="<?= route('admin.setting.email.template', ['type' => 'resset_password']) ?>" class="btn btn-soft btn-primary btn-sm">
                    قالب ایمیل ریست رمز عبور
                </a>
            </li>
        </ul>
    </div>
@endsection