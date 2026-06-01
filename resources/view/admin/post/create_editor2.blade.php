@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ایجاد مقاله (ادیتور ساده)</title>
<script src="<?= asset('tinymce/tinymce.min.js'); ?>"></script>
<script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>

@endsection

@section('content')
<style>
.tox-statusbar__branding{
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


.tox-menubar button span{
    font-size: 14px !important;
}

</style>

    <div class="form_cust w-full md:w-full m-auto">
<h1 class="text-lg md:text-3xl mb-5">مقاله جدید</h1>
        <div class="w-full bg-slate-100 m-auto p-2 rounded-lg">
            
            <form method="POST" action="/articles/store">

    <textarea id="editor" name="content"></textarea>

</form>

        </div>

<script>
    tinymce.init({
  selector: '#editor',
  license_key: 'gpl',
  promotion: false,
  language: 'fa',
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

</script>



    </div>

    <!-- success alert -->

     <!-- <div class=" alert alert-primary alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out" role="alert" id="dismiss-alert-success">
          <span class="icon-[tabler--circle-check] shrink-0 size-6"></span>
      <p>Dive into our platform to discover exciting new features and updates.</p>
      <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-success" aria-label="Close Button">
        <span class="icon-[tabler--x] size-5"></span>
      </button>
    </div>  -->
@endsection