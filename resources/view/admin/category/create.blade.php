@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | ایجاد دسته بندی</title>
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
    <div class="form_cust w-full md:w-full m-auto">
        <form class="form-my lg:mt-6" action="<?= route('admin.category.store') ?>" method="post"
            enctype="multipart/form-data">
            <div class="w-full md:w-3/4 m-auto flex flex-col md:space-y-13 space-y-3">
                <h1 class="text-xl">دسته بندی جدید</h1>
                <div
                    class="divider divider-dashed after:!border-t-2 before:!border-t-2 md:after:!border-t-4 md:before:!border-t-4">
                    <span class="icon-[line-md--circle] text-slate-300 size-13 md:size-17"></span></div>
                <div class="flex flex-col md:flex-row gap-7">

                    <div class="join w-full md:w-1/2 m-auto shadow-lg shadow-indigo-500/30 rounded-md">
                        <div class="input-floating w-full join-item">

                            <input type="text" placeholder="نام دسته بندی"
                                class="input !border-blue-400 no-focus !text-sm lg:!text-md input-sm lg:input-md lg:!h-12"
                                style="border-end-end-radius: 0px; border-top-left-radius:0px;" id="floatingInput"
                                name="name" value="<?= old('name'); ?>" />

                            <label class="input-floating-label lg:pt-4.5 lg:pr-1 !text-sm lg:!text-md"
                                for="floatingInput">نام
                                دسته بندی</label>
                        </div>

                        <select
                            class="select join-item !w-7/10 md:!w-1/2 !text-xs lg:!text-md lg:!h-12 select-sm lg:select-md !border-blue-400"
                            aria-label="select" name="parent_id" id="select_costum">
                            <option selected value="" class="text-xs lg:text-md">دسته بندی والد</option>
                            <?php echo buildCategoryTree($categories); ?>
                        </select>

                    </div>

                    <div class="w-full md:w-1/2">

                        <div class="textarea  w-full !border-blue-400 shadow-lg shadow-indigo-500/30">
                            <span
                                class="icon-[streamline-plump-color--description-flat] text-base-content/80 mt-2 mx-4 size-5.5 md:size-7 shrink-0 mt-2.5"></span>
                            <div class="textarea-floating grow">
                                
                                <textarea rows="1" placeholder="توضیحات" id="textareaFloatingMedium" name="description"
                                    value="<?= old('description'); ?>" class=" placeholder:!text-[#dad4ff]"></textarea>

                                <label class="textarea-floating-label" for="textareaFloatingMedium">توضیحات ...</label>
                            </div>
                        </div>

                    </div>
                </div>
                <div
                    class="divider divider-dashed after:!border-t-2 before:!border-t-2 md:after:!border-t-4 md:before:!border-t-4">
                    <span class="icon-[line-md--circle] text-slate-300 size-13 md:size-17"></span></div>

                <input type="submit"
                    class="btn btn-primary btn-block md:btn-wide btn-outline !border-[0.15rem] !border-dashed  btn-sm lg:btn-md"
                    value="ذخیره">

            </div>
        </form>
    </div>



@endsection