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
        <h1 class="text-3xl">کاربران</h1>

        <ul class="menu lg:menu-horizontal">
            <li>
                <a href="<?= route('admin.users.type' , ['type' => 'all']) ?>" class="btn btn-soft btn-primary btn-sm">
                    تمام کاربران
                </a>
            </li>
            <li>
                <a href="<?= route('admin.users.type' , ['type' => 'admin2']) ?>" class="btn btn-soft btn-primary btn-sm">
                    ادمین های دوم
                </a>
            </li>
            <li>
                <a href="<?= route('admin.users.type' , ['type' => 'editor']) ?>" class="btn btn-soft btn-primary btn-sm">
                    ادیتور ها
                </a>

            </li>
            <li>
                <a href="<?= route('admin.users.type' , ['type' => 'admin_seo']) ?>" class="btn btn-soft btn-primary btn-sm">
                    مدیران سیو
                </a>

            </li>
            <li>
                <a href="<?= route('admin.users.type' , ['type' => 'normal']) ?>" class="btn btn-soft btn-primary btn-sm">
                    کاربران معمولی
                </a>

            </li>
        </ul>
        <div>
            <a class="btn btn-primary btn-sm" href="<?= route('admin.users.create'); ?>">کاربر جدید</a>
        </div>
    </div>
    <div class="border-base-content/25 w-full overflow-x-auto border">
        <table class="table table-fixed border-separate">
            <thead class="bg-slate-100">
                <tr>
                    <th class="w-px whitespace-nowrap !text-right">id</th>
                    <th>کاربر</th>
                    <th>نام کاربری</th>
                    <th>ایمیل</th>
                    <th>نقش</th>
                    <th>تاریخ ورود</th>
                    <th class=" text-center">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="avatar">
                                <div class="bg-base-content/10 h-10 w-10 rounded-full">
                                    <img src="<?= isset($user->avatar) ? $user->avatar : ''; ?>" alt="avatar">
                                </div>
                            </div>
                            <div>
                                <div class="font-medium">
                                    <?= isset($user->first_name) ? $user->first_name . ' ' . $user->last_name : 'کاربر حذف شده'; ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><?= $user->username ?></td>
                    <td><?= $user->email ?></td>
                    <td><span class="badge badge-soft badge-success text-xs"><?= $user->user_type ?></span>
                    </td>
                    <td><?= jdate('d F Y', strtotime($user->created_at)); ?></td>
                    <td class="flex flex-row self-center justify-center">
                        <a href="<?= route('admin.users.edit', ['id' => $user->id]) ?>"
                            class="btn btn-circle btn-text btn-sm" aria-label="Action button"><span
                                class="icon-[tabler--pencil] size-5"></span></a>

                        <form method="post" action="<?= route('admin.users.destroy', ['id' => $user->id]) ?>">
                            <input type="hidden" value="delete" name="_method">
                            <button type="submit" class="btn btn-circle btn-text btn-sm" aria-label="Action button"><span
                                    class="icon-[tabler--trash] size-5"></span></button>
                        </form>

                        <button class="btn btn-circle btn-text btn-sm" aria-label="Action button"><span
                                class="icon-[tabler--dots-vertical] size-5"></span></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
@endsection