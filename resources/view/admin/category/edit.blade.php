@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ویرایش دسته بندی</title>
@endsection

@section('content')
    <style>
        #floatingInput:focus {
            border: solid 1px #d7d6ff !important;
        }

        #select_costum:focus {
            border: 0 !important;
        }
    </style>
    <?php 
        // echo flash($category->name , 'دسته بندی با موفقیت آپدیت شد');
    $errorName = errorClass('name');
    $errorParent_id = errorClass('parent_id');
    $errorDescription = errorClass('description');
             ?>
    <div class="form_cust w-full md:w-full m-auto">

        <div class="w-full md:w-3/4 m-auto">
            <!-- error name alert -->
            <div class="<?= $errorName['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-name">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorName['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-name"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <!-- error parent_id alert -->
            <div class="<?= $errorParent_id['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-parent-id">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorParent_id['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-parent-id"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>

            <!-- error description alert -->
            <div class="<?= $errorDescription['class_error']; ?> alert alert-error alert-soft flex items-center removing:translate-x-5 removing:opacity-0 gap-4 transition duration-300 ease-in-out"
                role="alert" id="dismiss-alert-error-description">
                <span class="icon-[tabler--alert-triangle] shrink-0 size-6"></span>
                <p><?= $errorDescription['message_error']; ?></p>
                <button class="ms-auto cursor-pointer leading-none" data-remove-element="#dismiss-alert-error-description"
                    aria-label="Close Button">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>
        </div>

        <form class="form-my lg:mt-6" action="<?= route('admin.category.update', [$category->id]); ?>" method="post"
            enctype="multipart/form-data">
            <div class="w-full md:w-3/4 m-auto flex flex-col md:space-y-10 space-y-3">
                <h1 class="text-xl">دسته بندی جدید</h1>

                <div
                    class="divider divider-dashed after:!border-t-2 before:!border-t-2 md:after:!border-t-4 md:before:!border-t-4">
                    <span class="icon-[line-md--circle] text-slate-300 size-13 md:size-17"></span>
                </div>
                <div class="flex flex-col md:flex-row gap-7">

                    <div class="join w-full md:w-1/2 m-auto shadow-lg shadow-indigo-500/30 rounded-md">
                        <div class="input-floating w-full join-item">
                            <input type="hidden" name="_method" value="put">
                            <input type="hidden" name="id" value="<?= $category->id ?>">
                            <input type="text" placeholder="نام دسته بندی"
                                class="input !border-blue-400 no-focus !text-sm lg:!text-md input-sm lg:input-md lg:!h-12"
                                style="border-end-end-radius: 0px; border-top-left-radius:0px;" id="floatingInput"
                                name="name" value="<?= $category->name; ?>" />

                            <label class="input-floating-label lg:pt-4.5 lg:pr-1 !text-sm lg:!text-md"
                                for="floatingInput">نام
                                دسته بندی</label>
                        </div>

                        <select
                            class="select join-item !w-7/10 md:!w-1/2 !text-xs lg:!text-md lg:!h-12 select-sm lg:select-md !border-blue-400"
                            aria-label="select" name="parent_id" id="select_costum">
                            <?php 
                                if ($category->parent_id == null) {

        echo '<option selected value="" class="text-xs lg:text-md">دسته بندی والد</option>';

        }
    else {
        echo '<option value="" class="text-xs lg:text-md">دسته بندی والد</option>';

        echo buildCategoryTreeSelect($category_all, $category->parent_id);
        }
    echo buildCategoryTreeEdit($category->parent_id, $category->id, $category_all); 
                                 ?>
                        </select>

                    </div>

                    <div class="w-full md:w-1/2">

                        <div class="textarea  w-full !border-blue-400 shadow-lg shadow-indigo-500/30">
                            <span
                                class="icon-[streamline-plump-color--description-flat] text-base-content/80 mt-2 mx-4 size-5.5 md:size-6 shrink-0 mt-2.5"></span>
                            <div class="textarea-floating grow">

                                <textarea rows="1" placeholder="توضیحات" id="textareaFloatingMedium" name="description"
                                    value="<?= $category->description; ?>"
                                    class=" placeholder:!text-[#dad4ff] !text-sm lg:!text-md"><?= $category->description; ?></textarea>

                                <label class="textarea-floating-label !text-sm lg:!text-md"
                                    for="textareaFloatingMedium">توضیحات ...</label>
                            </div>
                        </div>

                    </div>
                </div>
                <div
                    class="divider divider-dashed after:!border-t-2 before:!border-t-2 md:after:!border-t-4 md:before:!border-t-4">
                    <span class="icon-[line-md--circle] text-slate-300 size-13 md:size-17"></span>
                </div>

                <input type="submit"
                    class="btn btn-primary btn-block md:btn-wide btn-outline !border-[0.15rem] !border-dashed  btn-sm lg:btn-md"
                    value="ذخیره">

            </div>
        </form>
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