@extends('admin.layouts.app')

@section('head-tag')
    <title>ادمین | دسته بندی</title>
    <script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
    <script src="<?= asset('datatables.net/js/dataTables.min.js'); ?>"></script>
    <script src="<?= asset('flyonui/flyonui.js'); ?>"></script>
    <style>
        .dt-search {
            display: none !important;
        }

        .dt-layout-start {
            display: none !important;
        }

        .dt-layout-end {
            display: none !important;
        }
    </style>
    <script>
        window.addEventListener('load', () => {
            const available = document.querySelector('#select-stock')
            console.log('available', available)
            const { dataTable } = new HSDataTable('#datatable-filter')

            dataTable.search.fixed('stock', (searchStr, data, index) => {
                const isAvaiable = available.value === 'all' ? '' : available.value
                const parser = new DOMParser()
                const name = parser.parseFromString(data[3], 'text/html').body.textContent.trim()

                return isAvaiable === name || isAvaiable === ''
            })
            available.addEventListener('change', () => dataTable.draw())
        })
    </script>

@endsection

@section('content')

    <div class="overflow-x-auto w-full">
        <div class="mb-5">
            <a href="<?= route('admin.category.create') ?>" class="btn btn-primary rounded btn-sm lg:btn-md">ایجاد دسته
                بندی</a>
        </div>

        <div id="datatable-filter"
            class="bg-base-100 --prevent-on-load-init flex flex-col rounded-md shadow-base-300/20 shadow-sm" data-datatable='{
              "pageLength": 5,
              "pagingOptions": {
                "pageBtnClasses": "btn btn-text btn-circle btn-sm"
              },  
              "selecting": true,
              "rowSelectingOptions": {
                "selectAllSelector": "#datatable-filter-select-all-rows",
                "individualSelector": ".datatable-filter-select-row"
              },
              "language": {
                  "zeroRecords": "<div class=\"py-10 px-5 flex flex-col justify-center items-center text-center\"><span class=\"icon-[tabler--search] shrink-0 size-6 text-base-content\"></span><div class=\"max-w-sm mx-auto\"><p class=\"mt-2 text-sm text-base-content/80\">No search results</p></div></div>"
                }
            }'>
            <div class="border-base-content/25 flex items-center border-b px-5 py-3 gap-3">
                <div class="input input-sm max-w-60">
                    <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                    <label class="sr-only" for="filter-search"></label>
                    <input type="search" class="grow" placeholder="Search for items" id="filter-search"
                        data-datatable-search="" />
                </div>
                <div class="flex flex-1 items-center justify-end gap-3">
                    <!-- Select -->
                    <select data-select='{
                      "placeholder": "Select option...",
                      "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                      "toggleClasses": "advance-select-toggle advance-select-sm",
                      "dropdownClasses": "advance-select-menu w-24 max-sm:w-16 bg-white shadow-md pr-2 rounded",
                      "optionClasses": "advance-select-option selected:select-active",
                      "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-3 text-primary hidden selected:block \"></span></div>",
                      "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-5 -translate-y-1/2 \"></span>"
                      }' class="hidden" data-datatable-page-entities="">
                        <option value="5" selected="">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                    <!-- End Select -->
                    <select data-select='{
                      "placeholder": "Select option...",
                      "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                      "toggleClasses": "advance-select-toggle advance-select-sm max-sm:w-20 w-36",
                      "dropdownClasses": "advance-select-menu max-sm:w-28 w-full bg-white shadow-md pr-2 rounded",
                      "optionClasses": "advance-select-option selected:select-active",
                      "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-3 text-primary hidden selected:block \"></span></div>",
                      "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-23 -translate-y-1/2 \"></span>"
                      }' class="hidden" id="select-stock">
                        <option value="all">All</option>
                        <option value="In Stock">In Stock</option>
                        <option value="Out of Stock">Out of Stock</option>
                        <option value="Limited">Limited</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="table table-striped">
                            <thead>
                                <tr class="border-0 bg-base-300/20 *:first:rounded-s-md *:last:rounded-e-md">
                                    <th scope="col" class="--exclude-from-ordering w-3.5 pe-0">
                                        <div class="flex h-5 items-center">
                                            <input id="datatable-filter-select-all-rows" type="checkbox"
                                                class="checkbox checkbox-sm" />
                                            <label for="datatable-filter-select-all-rows" class="sr-only">Checkbox</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="group w-fit">
                                        <div class="flex items-center justify-between">
                                            نام دسته بندی
                                            <span
                                                class="icon-[tabler--chevron-up] datatable-ordering-asc:block hidden"></span>
                                            <span
                                                class="icon-[tabler--chevron-down] datatable-ordering-desc:block hidden"></span>
                                        </div>
                                    </th>
                                    <th scope="col" class="group w-fit">
                                        <div class="flex items-center justify-between">
                                            تعداد مقالات
                                            <span
                                                class="icon-[tabler--chevron-up] datatable-ordering-asc:block hidden"></span>
                                            <span
                                                class="icon-[tabler--chevron-down] datatable-ordering-desc:block hidden"></span>
                                        </div>
                                    </th>
                                    <th scope="col" class="group w-fit">
                                        <div class="flex items-center justify-between">
                                            توضیحات
                                            <span
                                                class="icon-[tabler--chevron-up] datatable-ordering-asc:block hidden"></span>
                                            <span
                                                class="icon-[tabler--chevron-down] datatable-ordering-desc:block hidden"></span>
                                        </div>
                                    </th>
                                    <th scope="col" class="group w-fit">
                                        <div class="flex items-center justify-between">
                                            دسته والد
                                            <span
                                                class="icon-[tabler--chevron-up] datatable-ordering-asc:block hidden"></span>
                                            <span
                                                class="icon-[tabler--chevron-down] datatable-ordering-desc:block hidden"></span>
                                        </div>
                                    </th>
                                    <th scope="col" class="--exclude-from-ordering">ویرایش/حذف</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

    use App\Category;

    $pr = 'اصلی';
    foreach ($categories as $category)
    {
        if ($category->parent_id != NULL)
        {
            $pr = Category::find($category->parent_id)->name;
        }else{
            $pr = 'اصلی';
        }
                        ?>

                                <tr id="<?= $category->id ?>">

                                    <td class="w-3.5 pe-0">
                                        <div class="flex h-5 items-center">
                                            <input id="table-filter-1" type="checkbox"
                                                class="checkbox checkbox-sm datatable-filter-select-row"
                                                data-datatable-row-selecting-individual="" />
                                            <label for="table-filter-1" class="sr-only">Checkbox</label>
                                        </div>
                                    </td>
                                    <td><?= $category->name ?></td>
                                    <td><?= count(Category::find($category->id)->posts()->get()); ?></td>

                                    <td class="!max-w-[200px] !break-words !whitespace-normal !min-w-[200px]">
                                        <?= $category->description; ?>
                                    </td>

                                    <td><span class="badge badge-soft badge-success badge-sm"><?= $pr; ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= route('admin.category.edit', [$category->id]) ?>"
                                            class="btn btn-circle btn-text btn-sm" aria-label="Action button">
                                            <span class="icon-[tabler--pencil] size-5"></span>
                                        </a>
                                        <div class="join join-vertical gap-0">
                                            <div class=" join-item join join-horizontal">
                                                <form action="<?= route('admin.category.destroy', [$category->id]) ?>"
                                                    method="post" class="inline ajax-form join-item">
                                                    <input type="hidden" name="_method" value="delete">
                                                    <input type="hidden" name="id" value="<?= $category->id ?>">
                                                    <button type="submit" class="btn btn-circle btn-text btn-sm"
                                                        aria-label="Action button">
                                                        <span class="icon-[tabler--trash] size-5"></span>
                                                    </button>
                                                </form>
                                                <a class="btn btn-circle btn-text btn-sm join-item"
                                                    aria-label="Action button">
                                                    <span class="icon-[tabler--dots-vertical] size-5"></span>
                                                </a>
                                            </div>
                                            <div class="loading-place-<?= $category->id ?>">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                                <script>
                                    $(document).on("submit", ".ajax-form", function (e) {
                                        e.preventDefault(); // جلوگیری از ارسال فرم به صورت عادی
                                        let windowWidth = $(window).width();
                                        let form = $(this);
                                        let categoryId = form.find('input[name="id"]').val(); // گرفتن ID دسته بندی
                                        let targetRow = $("#" + categoryId); // گرفتن ردیف جدول مربوطه
                                        let loadingPlace = targetRow.find('.loading-place-' + categoryId); // گرفتن div برای نمایش loading

                                        // --- اضافه کردن عناصر loading ---
                                        let overlayDiv = $('<div class="bg-base-100/50 absolute start-0 top-0 size-full"></div>');
                                        let loadingSpan = $('<span class="loading loading-dots md:!loading-xl lg:!loading-xl text-error"></span>');

                                        // برای اینکه loading spinner در مرکز قرار بگیرد، از flexbox استفاده می‌کنیم
                                        let loadingWrapper = $('<div class="block md:!flex justify-center items-center h-full w-full"></div>');
                                        loadingWrapper.append(loadingSpan);
                                        overlayDiv.append(loadingWrapper);



                                        // اضافه کردن overlay و spinner به loading-place
                                        loadingPlace.append(overlayDiv);
                                        loadingPlace.addClass('!relative md:!static');
                                        if (windowWidth < 768) {
                                            loadingPlace.css('padding-bottom', '10px');
                                            loadingPlace.css('margin-top', '15px');
                                            loadingPlace.css('margin-bottom', '15px');
                                        }
                                        // disabled کردن دکمه ها تا کاربر دوباره کلیک نکند
                                        form.find('button[type="submit"], a').prop('disabled', true);
                                        form.find('button[type="submit"]').css('opacity', '0.6'); // نمایش بصری disabled شدن

                                        $.ajax({
                                            url: form.attr("action"),
                                            type: form.attr("method") || "POST",
                                            data: form.serialize(),
                                            success: function (response) {
                                                // --- حذف عناصر loading و نمایش پیام موفقیت (اختیاری) ---
                                                setTimeout(function () {
                                                    loadingPlace.empty().removeClass('relative'); // پاک کردن محتویات loading-place
                                                    targetRow.remove(); // حذف کل ردیف جدول
                                                    loadingPlace.css('padding-bottom', '0px');
                                                    loadingPlace.css('margin-top', '0px');
                                                    loadingPlace.css('margin-bottom', '0px');
                                                }, 1000);

                                            },
                                            error: function (xhr, status, error) {
                                                setTimeout(function () {
                                                    console.error("Error submitting form:", status, error);
                                                    // --- حذف عناصر loading و نمایش پیام خطا ---
                                                    loadingPlace.empty().removeClass('relative');
                                                    form.find('button[type="submit"], a').prop('disabled', false);
                                                    form.find('button[type="submit"]').css('opacity', '1');
                                                    loadingPlace.css('padding-bottom', '0px');
                                                    loadingPlace.css('margin-top', '0px');
                                                    loadingPlace.css('margin-bottom', '0px');
                                                    // می توانید پیام خطای جامع تری هم نمایش دهید
                                                    alert("خطا در ارسال اطلاعات: " + error);
                                                }, 1000);
                                                // پاک کردن محتویات loading-place
                                                // نمایش پیام خطا در جایی مناسب، مثلاً در خود loading-place یا یک notification
                                                // اگر می خواهید پیام خطا را در همان جا نمایش دهید:
                                                // loadingPlace.html('<div class="text-red-500">خطا در حذف!</div>');

                                                // فعال کردن دوباره دکمه ها

                                            }
                                        });
                                    });


                                </script>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div
                class="border-base-content/25 flex items-center justify-between gap-3 border-t p-3 max-md:flex-wrap max-md:justify-center">
                <div class="text-base-content/80 text-sm" data-datatable-info="">
                    صفحه
                    <span data-datatable-info-from="1"></span>
                    تا
                    <span data-datatable-info-to="30"></span>
                    از
                    <span data-datatable-info-length="50"></span>
                    مقدار
                </div>
                <div class="flex hidden items-center space-x-1" data-datatable-paging="">
                    <button type="button" class="btn btn-text btn-circle btn-sm" data-datatable-paging-prev="">
                        <span class="icon-[tabler--chevrons-left] size-4.5 rtl:rotate-180"></span>
                        <span class="sr-only">قبلی</span>
                    </button>
                    <div class="[&>.active]:text-bg-soft-primary flex items-center space-x-1"
                        data-datatable-paging-pages=""></div>
                    <button type="button" class="btn btn-text btn-circle btn-sm" data-datatable-paging-next="">
                        <span class="sr-only">بعدی</span>
                        <span class="icon-[tabler--chevrons-right] size-4.5 rtl:rotate-180"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>



@endsection