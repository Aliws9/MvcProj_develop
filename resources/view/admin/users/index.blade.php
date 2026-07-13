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
<div class="border-base-content/25 w-full overflow-x-auto border">
  <table class="table">
    <thead>
      <tr>
        <th>نام کامل</th>
        <th>ایمیل</th>
        <th>نقش</th>
        <th>تاریخ ورود</th>
        <th>عملیات</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
      <tr>
        <td><?= $user->first_name . ' ' . $user->last_name ?></td>
        <td><?= $user->email ?></td>
        <td><span class="badge badge-soft badge-success text-xs"><?= $user->user_type ?></span></td>
        <td><?= jdate('d F Y', strtotime($user->created_at)); ?></td>
        <td>
          <button class="btn btn-circle btn-text btn-sm" aria-label="Action button"><span class="icon-[tabler--pencil] size-5"></span></button>
          <button class="btn btn-circle btn-text btn-sm" aria-label="Action button"><span class="icon-[tabler--trash] size-5"></span></button>
          <button class="btn btn-circle btn-text btn-sm" aria-label="Action button"><span class="icon-[tabler--dots-vertical] size-5"></span></button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
@endsection