@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ایجاد مقاله (ادیتور ساده)</title>
<script src="<?= asset('tinymce/lang/fa.js'); ?>"></script>
        <!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/nhfbg3frjs0iz9ko0zfoulp8gqscvclx5p4q9klam9037hrr/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
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
.tox-promotion{
    display: none !important;
}

</style>

    <div class="form_cust w-full md:w-full m-auto">
<h1 class="text-lg md:text-3xl mb-5">مقاله جدید</h1>
        <div class="w-full bg-slate-100 m-auto p-2 rounded-lg h-[120vh] lg:h-[100vh]">
        


<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    language: 'fa',
    promotion: false,
    toolbar_mode: 'sliding',
    height: '100%',
    plugins: [
      // Core editing features
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      // Your account includes a free trial of TinyMCE premium features
      // Try the most popular premium features until Jun 13, 2026:
      'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'tinymceai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | tinymceai-chat tinymceai-quickactions tinymceai-review | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    tinymceai_token_provider: async () => {
      await fetch(`https://demo.api.tiny.cloud/1/nhfbg3frjs0iz9ko0zfoulp8gqscvclx5p4q9klam9037hrr/auth/random`, { method: "POST", credentials: "include" });
      return { token: await fetch(`https://demo.api.tiny.cloud/1/nhfbg3frjs0iz9ko0zfoulp8gqscvclx5p4q9klam9037hrr/jwt/tinymceai`, { credentials: "include" }).then(r => r.text()) };
    },
    uploadcare_public_key: '8cccaeffedb8951d0b85',
  });
</script>

<textarea class="h-full">
  متن خود را بنویسید
</textarea>

        </div>





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