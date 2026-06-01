<div class="w-full bg-white py-3 px-4 shadow-lg relative flex rounded-lg">
    <div class="w-1/2 flex justify-end order-2">



        <div class="dropdown relative inline-flex" tabindex="0">

            <button id="dropdown-footer" type="button" class="dropdown-toggle flex" aria-haspopup="menu"
                aria-expanded="false" aria-label="Dropdown">
                <!-- name admin -->
                <span
                    class="ml-5 self-center text-sm lg:text-lg"><?= \System\Auth\Auth::user()->first_name . ' ' . \System\Auth\Auth::user()->last_name; ?></span>

                <!-- avatar admin -->
                <div class="w-12 h-12 md:w-16 md:h-16 self-center flex justify-center">
                    <span class="bg-slate-300 rounded-[50%] self-center inline-block h-full">

                        <img src="<?= asset(\System\Auth\Auth::user()->avatar); ?>" alt="avatar">
                    </span>

                </div>

            </button>

            <ul class="dropdown-menu dropdown-open:opacity-100 hidden min-w-60" role="menu" aria-orientation="vertical"
                aria-labelledby="dropdown-footer">
                <li><a class="dropdown-item" href="#">ویرایش پروفایل</a></li>
                <li class="dropdown-footer gap-2">
                    <button class="btn btn-error btn-soft btn-block">خروج</button>
                </li>
            </ul>

        </div>

    </div>

    <div class="w-1/2 self-center float-right order-1">
        <div class="w-fit">


            <button type="button" class="btn btn-soft btn-sm lg:!hidden" aria-haspopup="dialog" aria-expanded="false"
                aria-controls="overlay-example" data-overlay="#overlay-example"><span
                    class=" icon-[tabler--menu] size-7"></span></button>

            <div id="overlay-example" class="overlay overlay-open:translate-x-0 !w-5/6 drawer drawer-start hidden"
                role="dialog" tabindex="-1">
                <div class="drawer-header">
                    <h3 class="drawer-title">منوی مدیریت</h3>
                    <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close"
                        data-overlay="#overlay-example">
                        <span class="icon-[tabler--x] size-5"></span>
                    </button>
                </div>
                <div class="drawer-body !p-2">
                    <div class="w-full">

                        <a href="#">
                            <div
                                class="w-full bg-green-200 p-2 border-s-5 lg:border-s-7 border-green-500 flex justify-between hover:bg-green-400 transition  ease-in-out hover:border-yellow-500 pointer cursor-pointer rounded-tl-2xl mb-3">
                                <span class="text-sm lg:text-base text-green-700 w-1/1 hover:text-white">مشاهده
                                    سایت</span>
                                <span><img src="<?php echo asset('/icon/admin/svg/green/hand_cursor2.svg'); ?>" alt=""
                                        class="w-5 h-5 lg:w-6 lg:h-6 inline-block"></span>
                            </div>
                        </a>

                        <div class="p-0">

                            <ul class="menu accordion w-full space-y-0.5 bg-blue-50 !text-sm">
                <li>
                    <a href="<?= route('admin.index') ?>" class="<?php if ($current == 'admin')
    echo 'menu-active';
else
    echo '';  ?>">
                        <span class="icon-[tabler--home] size-5"></span>
                        داشبورد
                    </a>
                </li>
                <li class="space-y-0.5">
                    <a class="collapse-toggle <?php if ($current == 'admin/post' || $current == 'admin/post/create1' || $current == 'admin/post/create2')
    echo 'menu-active open';
else
    echo '';  ?> collapse-open:bg-base-content/10" id="menu-app-post" data-collapse="#menu-app-collapse3">
                        <span class="icon-[tabler--article] size-5"></span>
                        مقالات
                        <span
                            class="icon-[tabler--chevron-down] collapse-open:rotate-180 size-4 transition-all duration-300"></span>
                    </a>

                    <ul id="menu-app-collapse3" class="collapse w-auto space-y-0.5 overflow-hidden transition-[height] duration-300 <?php if ($current == 'admin/post' || $current == 'admin/post/create1' || $current == 'admin/post/create2')
    echo 'open';
else
    echo 'hidden';  ?>" aria-labelledby="menu-app-post">


                        <li>
                            <a href="#">
                                <span class="icon-[ooui--articles-rtl] size-5"></span>
                                همه مقالات
                            </a>
                        </li>

                        <li>
                            <a class="collapse-toggle <?php if ($current == 'admin/post/create1' || $current == 'admin/post/create2')
    echo 'menu-active open';
else
    echo '';  ?> collapse-open:bg-base-content/10" id="menu-app2" data-collapse="#menu-post-editor2">
                                <span class="icon-[jam--write] size-5"></span>
                                مقاله جدید
                                <span
                                    class="icon-[tabler--chevron-down] collapse-open:rotate-180 size-4 transition-all duration-300"></span>
                            </a>

                            <ul id="menu-post-editor2" class="collapse w-auto space-y-0.5 overflow-hidden transition-[height] duration-300 <?php if ($current == 'admin/post/create1' || $current == 'admin/post/create2')
    echo 'open';
else
    echo 'hidden';  ?>" aria-labelledby="menu-app2">

                                <li>
                                    <a href="<?= route('admin.post.create1'); ?>" class="<?php if ($current == 'admin/post/create1')
    echo 'menu-active';
else
    echo '';  ?>">
                                        <span class="icon-[fluent--tab-new-24-filled] size-5"></span>
                                        ویرایشگر ساده
                                    </a>
                                </li>

                                <li>
                                    <a href="#" class="">
                                        <span class="icon-[fluent--tab-new-24-filled] size-5"></span>
                                        ویرایشگر پیشرفته
                                    </a>
                                </li>

                            </ul>

                        </li>

                    </ul>
                </li>

                <li class="space-y-0.5">
                    <a class="collapse-toggle collapse-open:bg-base-content/10 <?php if ($current == 'admin/category' OR $current == 'admin/category/create')
    echo 'menu-active open';
else
    echo '';  ?>" id="menu-category2" data-collapse="#menu-category-collapse2">
                        <span class="icon-[tabler--category] size-5"></span>
                        دسته بندی ها
                        <span
                            class="icon-[tabler--chevron-down] collapse-open:rotate-180 size-4 transition-all duration-300"></span>
                    </a>
                    <ul id="menu-category-collapse2" class="<?php if ($current == 'admin/category' || $current == 'admin/category/create')
    echo 'open';
else
    echo 'hidden';  ?> collapse w-auto space-y-0.5 overflow-hidden transition-[height] duration-300"
                        aria-labelledby="menu-category2">
                        <li>
                            <a href="<?= route('admin.category.index'); ?>" class="<?php if ($current == 'admin/category')
    echo 'menu-active';
else
    echo '';  ?>">
                                <span class="icon-[iconamoon--category-fill] size-5"></span>
                                همه دسته بندی ها
                            </a>
                        </li>
                        <li>
                            <a href="<?= route('admin.category.create'); ?>" class="<?php if ($current == 'admin/category/create')
    echo 'menu-active';
else
    echo '';  ?>">
                                <span class="icon-[fluent--tab-new-24-filled] size-5"></span>
                                دسته بندی جدید
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <span class="icon-[material-symbols--comment] size-5"></span>
                        نظرات
                    </a>
                </li>

                <li class="space-y-0.5">
                    <a class="collapse-toggle collapse-open:bg-base-content/10 <?php if ($current == 'admin/media' || $current == 'admin/media/create')
    echo 'menu-active open';
else
    echo '';  ?>" id="menu-media2" data-collapse="#menu-media-collapse2">
                        <span class="icon-[qlementine-icons--media-16] size-5"></span>
                        رسانه
                        <span
                            class="icon-[tabler--chevron-down] collapse-open:rotate-180 size-4 transition-all duration-300"></span>
                    </a>
                    <ul id="menu-media-collapse2" class="<?php if ($current == 'admin/media' || $current == 'admin/media/create')
    echo 'open';
else
    echo 'hidden';  ?> collapse w-auto space-y-0.5 overflow-hidden transition-[height] duration-300"
                        aria-labelledby="menu-media2">
                        <li>
                            <a href="#">
                                <span class="icon-[flowbite--upload-solid] size-5"></span>
                                رسانه جدید
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="icon-[material-symbols--perm-media] size-5"></span>
                                همه رسانه ها
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="space-y-0.5">
                    <a class="collapse-toggle collapse-open:bg-base-content/10 <?php if ($current == 'admin/setting' || $current == 'admin/setting/create')
    echo 'menu-active open';
else
    echo '';  ?>" id="menu-setting2" data-collapse="#menu-setting-collapse2">
                        <span class="icon-[tabler--settings] size-5"></span>
                        تنظیمات
                        <span
                            class="icon-[tabler--chevron-down] collapse-open:rotate-180 size-4 transition-all duration-300"></span>
                    </a>
                    <ul id="menu-setting-collapse2" class="<?php if ($current == 'admin/setting' || $current == 'admin/setting/create')
    echo 'open';
else
    echo 'hidden';  ?> collapse w-auto space-y-0.5 overflow-hidden transition-[height] duration-300"
                        aria-labelledby="menu-setting2">
                        <li>
                            <a href="#">
                                <span class="icon-[tabler--seo] size-5"></span>
                                تنظیمات SEO
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="icon-[fluent-mdl2--site-scan] size-5"></span>
                                هویت سایت
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="icon-[tdesign--menu] size-5"></span>
                                منو ها
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

                        </div>
                    </div>
                </div>
                <div class="drawer-footer">
                    <button type="button" class="btn btn-soft btn-secondary"
                        data-overlay="#overlay-example">بستن</button>
                </div>
            </div>



        </div>
    </div>
</div>