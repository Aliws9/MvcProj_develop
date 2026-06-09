/**
 * Global Media Manager
 * استفاده:
 *   window.MediaManager.open({ mode: 'input', target: '#field-id' })
 *   window.MediaManager.open({ mode: 'tinymce', editor: tinyMCE.activeEditor })
 */

(function ($) {
    'use strict';

    // ========== تنظیمات ==========
    const CONFIG = {
        listUrl: null,       // از init مقدار می‌گیره
        uploadUrl: null,
        deleteUrl: null,
        altUrl: null,
        imageExts: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        videoExts: ['mp4', 'webm', 'mov', 'avi'],
        audioExts: ['mp3', 'wav', 'ogg'],
    };

    // ========== State ==========
    let state = {
        mode: null,         // 'input' | 'tinymce'
        target: null,       // jQuery selector یا null
        editor: null,       // TinyMCE editor instance
        onSelect: null,     // callback
        filter: 'all',
        search: '',
        allItems: [],
        selectedItem: null,
        pond: null,         // FilePond instance
    };


    // ========== ساخت HTML مودال ==========
    function buildModal() {
        if ($('#mm-modal').length) return; // قبلاً ساخته شده

        const html = `
        <div id="mm-modal"
            class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle"
            role="dialog" tabindex="-1">
            <div class="modal-dialog border-3 border-blue-200 border-dashed rounded-xl shadow-lg !w-[95vw] md:!w-[70%] !h-full md:!h-auto" style="max-width: 100%;">
                <div class="modal-content !h-full md:!h-auto !max-h-[100%]" style="max-height: 85vh;">

                    <!-- Header -->
                    <div class="modal-header border-b-3 border-slate-200 pb-3">
                        <h3 class="modal-title text-lg font-semibold">مدیر رسانه</h3>
                        <button type="button" id="mm-close-btn"
                            class="btn btn-text btn-circle btn-sm absolute end-3 top-3">
                            <span class="icon-[tabler--x] size-5"></span>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body p-0 flex flex-col" style="height: 65vh; overflow: hidden;">

                        <!-- Tab Bar -->
                        <nav class="tabs tabs-bordered justify-start px-4 bg-blue-50 rounded shrink-0"
                            aria-label="media tabs" role="tablist">
                            <button type="button" class="tab active-tab:tab-active active mm-tab"
                                data-tab="browse" role="tab">
                                مرور رسانه‌ها
                            </button>
                            <button type="button" class="tab active-tab:tab-active mm-tab"
                                data-tab="upload" role="tab">
                                آپلود جدید
                            </button>
                        </nav>

                        <!-- Tab: Browse -->
                        <div id="mm-tab-browse" class="flex flex-col flex-1 overflow-hidden">

                            <!-- Toolbar -->
                            <div class="flex flex-wrap gap-2 items-center p-3 border-b-3 border-b-slate-200 shrink-0">
                                <div class="input input-sm max-w-52">
                                    <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                                    <input type="search" class="grow" placeholder="جستجو..." id="mm-search" />
                                </div>
                                <div class="flex gap-1 flex-wrap">
                                    <button class="btn btn-xs mm-filter active btn-primary" data-filter="all">همه</button>
                                    <button class="btn btn-xs mm-filter btn-soft" data-filter="image">تصاویر</button>
                                    <button class="btn btn-xs mm-filter btn-soft" data-filter="video">ویدیو</button>
                                    <button class="btn btn-xs mm-filter btn-soft" data-filter="audio">صدا</button>
                                    <button class="btn btn-xs mm-filter btn-soft" data-filter="other">سایر</button>
                                </div>
                            </div>

                            <!-- Content: Grid + Sidebar -->
                            <div class="flex flex-1 overflow-y-auto relative">

                                <!-- Grid -->
                                <div class="overflow-y-auto p-3 w-100 md:w-5/7">
                                    <div id="mm-grid" class="grid gap-2"
                                        style="grid-template-columns: repeat(auto-fill, minmax(110px, 1fr))">
                                        <div id="mm-loading" class="col-span-full flex justify-center py-16">
                                            <span class="loading loading-spinner loading-lg text-primary"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sidebar -->
                                <div class="absolute md:relative w-[90%] md:w-2/7 overflow-y-auto" id="sidebar_mm2">
                                <div id="mm-sidebar"
                                    class="hidden overflow-y-auto p-3 bg-slate-100 w-full border-3 border-slate-200 rounded-lg md:border-r-2 shadow-lg md:border-r-slate-200">
                                    <button id="close_detail2" class="block w-fit md:hidden"><span class="icon-[tabler--x] size-5"></span></button>
                                    <div id="mm-sidebar-content"></div>
                                </div>
                                </div>

                            </div>
                        </div>

                        <!-- Tab: Upload -->
                        <div id="mm-tab-upload" class="flex-1 p-4 overflow-y-auto">
                            <input type="file" id="mm-filepond" name="filepond" multiple
                                accept="image/*,video/*,audio/*,.pdf,.doc,.docx" class="!h-[90%] md:!h-[50%]">
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-t pt-3">
                        <button type="button" id="mm-cancel-btn" class="btn btn-soft btn-secondary">
                            انصراف
                        </button>
                        <button type="button" id="mm-insert-btn"
                            class="btn btn-primary" disabled>
                            <span class="icon-[tabler--check] size-4"></span>
                            درج رسانه
                        </button>
                    </div>

                </div>
            </div>
        </div>`;

        $('body').append(html);
        bindModalEvents();
    }

    // ========== رویدادهای مودال ==========
    function bindModalEvents() {

        // تب‌ها
        $(document).on('click', '.mm-tab', function () {
            const tab = $(this).data('tab');
            $('.mm-tab').removeClass('active');
            $(this).addClass('active');
            $('#mm-tab-browse, #mm-tab-upload').addClass('!hidden').css('display', '');
            $('#mm-tab-' + tab).removeClass('!hidden');
            // اگر رفتیم به تب browse، گرید رو refresh کن
            if (tab === 'browse') loadGrid();
        });

        // فیلتر
        $(document).on('click', '.mm-filter', function () {
            $('.mm-filter').removeClass('btn-primary active').addClass('btn-soft');
            $(this).removeClass('btn-soft').addClass('btn-primary active');
            state.filter = $(this).data('filter');
            renderGrid();
        });

        // جستجو با debounce
        let searchTimer;
        $(document).on('input', '#mm-search', function () {
            clearTimeout(searchTimer);
            state.search = $(this).val();
            searchTimer = setTimeout(renderGrid, 350);
        });

        // کلیک روی آیتم گرید
        $(document).on('click', '.mm-item', function () {
            $('.mm-item').removeClass('mm-selected');
            $(this).addClass('mm-selected');
            state.selectedItem = $(this).data();
            showSidebar(state.selectedItem);
            $('#mm-insert-btn').prop('disabled', false);
        });

        // دوبار کلیک = درج فوری
        $(document).on('dblclick', '.mm-item', function () {
            $('.mm-item').removeClass('mm-selected');
            $(this).addClass('mm-selected');
            state.selectedItem = $(this).data();
            doInsert();
        });

        // دکمه درج
        $(document).on('click', '#mm-insert-btn', function () {
            if (!state.selectedItem) return;
            doInsert();
        });

        // بستن مودال
        $(document).on('click', '#mm-close-btn, #mm-cancel-btn', function () {
            closeModal();
        });

        // کلیک بیرون از مودال
        $(document).on('click', '#mm-modal', function (e) {
            if ($(e.target).is('#mm-modal')) closeModal();
        });

        // حذف از سایدبار
        $(document).on('click', '#mm-delete-btn', function () {
            const id = $(this).data('id');
            if (!confirm('حذف شود؟')) return;
            deleteMedia(id);
        });
    }

    // ========== لود گرید ==========
    function loadGrid() {
        $('#mm-loading').show();
        $('#mm-grid').find('.mm-item').remove();

        $.ajax({
            url: CONFIG.listUrl,
            type: 'GET',
            success: function (data) {
                state.allItems = typeof data === 'string' ? JSON.parse(data) : data;
                $('#mm-loading').hide();
                renderGrid();
            },
            error: function () {
                $('#mm-loading').hide();
                $('#mm-grid').html('<p class="text-error col-span-full text-center py-8">خطا در بارگذاری فایل‌ها</p>');
            }
        });
    }

    // ========== رندر گرید ==========
    function renderGrid() {
        $('#mm-grid').find('.mm-item').remove();
        $('#mm-grid').find('.mm-empty').remove();

        const filtered = state.allItems.filter(item => {
            const fmt = (item.format || '').toLowerCase();
            const name = (item.file_name || '').toLowerCase();
            const srch = state.search.toLowerCase();

            const matchSearch = !srch || name.includes(srch);
            let matchFilter = true;

            if (state.filter === 'image') matchFilter = CONFIG.imageExts.includes(fmt);
            else if (state.filter === 'video') matchFilter = CONFIG.videoExts.includes(fmt);
            else if (state.filter === 'audio') matchFilter = CONFIG.audioExts.includes(fmt);
            else if (state.filter === 'other') {
                matchFilter = !CONFIG.imageExts.includes(fmt)
                            && !CONFIG.videoExts.includes(fmt)
                            && !CONFIG.audioExts.includes(fmt);
            }

            return matchSearch && matchFilter;
        });

        if (filtered.length === 0) {
            $('#mm-grid').append(
                '<div class="mm-empty col-span-full text-center py-16 text-base-content/50">فایلی یافت نشد</div>'
            );
            return;
        }

        filtered.forEach(item => {
            const el = buildGridItem(item);
            $('#mm-grid').append(el);
        });
    }

    // ========== ساخت آیتم گرید ==========
    function buildGridItem(item) {
        const fmt = (item.format || '').toLowerCase();
        const isImg = CONFIG.imageExts.includes(fmt);
        const isVid = CONFIG.videoExts.includes(fmt);
        const isAud = CONFIG.audioExts.includes(fmt);

        let inner = '';
        if (isImg) {
            inner = `<img src="${item.url}" alt="${item.file_name}"
                        class="w-full h-full object-cover absolute inset-0" loading="lazy">`;
        } else if (isVid) {
            inner = `<div class="flex flex-col items-center justify-center h-full gap-1 p-2">
                        <span class="icon-[tabler--video] size-8 text-primary"></span>
                        <span class="text-xs text-center break-all leading-tight line-clamp-2">${item.file_name}</span>
                     </div>`;
        } else if (isAud) {
            inner = `<div class="flex flex-col items-center justify-center h-full gap-1 p-2">
                        <span class="icon-[tabler--music] size-8 text-success"></span>
                        <span class="text-xs text-center break-all leading-tight line-clamp-2">${item.file_name}</span>
                     </div>`;
        } else {
            inner = `<div class="flex flex-col items-center justify-center h-full gap-1 p-2">
                        <span class="icon-[tabler--file] size-8 text-warning"></span>
                        <span class="text-xs text-center break-all leading-tight line-clamp-2">${item.file_name}</span>
                     </div>`;
        }

        const el = $(`
            <div class="mm-item relative cursor-pointer rounded-lg overflow-hidden bg-base-200
                        border-2 border-transparent hover:border-primary transition-all select-none"
                 style="aspect-ratio: 1;"
                 data-id="${item.id}"
                 data-url="${item.url}"
                 data-name="${item.file_name}"
                 data-format="${item.format}"
                 data-size="${item.size}"
                 data-width="${item.width || ''}"
                 data-alt="${item.alt || ''}"
                 data-height="${item.height || ''}">
                ${inner}
            </div>
        `);

        return el;
    }
        


function bindAltImageBlur() {
    $(document).on('blur', '.alt_image', function() {
        var $input = $(this);                // ذخیره ارجاع به input
        var $form = $input.closest('form.auto-form');
        
        if (!$form.length) {
            console.warn('فرم auto-form پیدا نشد');
            return;
        }
        
        var textValue = $input.val().trim();
        if (textValue === '') {
            return; // خالی باشد ارسال نکن
        }
        
        var formData = $form.serialize();
        
        // حذف پیام قبلی
        $form.find('.alt-status').remove();
        
        $.ajax({
            url: CONFIG.altUrl,
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $form.append('<div class="alt-status text-info text-xs mt-1">در حال ارسال...</div>');
            },
            success: function(response) {
                $form.find('.alt-status').remove();
                $form.append('<div class="alt-status text-success text-xs mt-1">✓ ذخیره شد</div>');
                // پس از ۲ ثانیه محو کن
                setTimeout(function() {
                    $form.find('.alt-status').fadeOut();
                }, 2000);
            },
            error: function(response) {
                $form.find('.alt-status').remove();
                $form.append('<div class="alt-status text-error text-xs mt-1">❌ خطا در ارسال</div>');
            }
        });
    });
}


$(document).ready(function() {
    // ... سایر کدهای اولیه
    bindAltImageBlur();   // فقط یک بار
});
    

    // ========== نمایش سایدبار ==========
    function showSidebar(item) {
        const fmt = (item.format || '').toLowerCase();
        const isImg = CONFIG.imageExts.includes(fmt);
        const isVid = CONFIG.videoExts.includes(fmt);
        const isAud = CONFIG.audioExts.includes(fmt);
        const sizeText = item.size > 1048576
            ? (item.size / 1048576).toFixed(2) + ' MB'
            : (item.size / 1024).toFixed(1) + ' KB';

        let preview = '';

        if(isVid){
            preview = `<video controls class="w-full mb-1 md:mb-3">
            <!-- منابع ویدیو در فرمت‌های مختلف برای پشتیبانی از مرورگرهای گوناگون -->
            <source src="${item.url}" type="video/mp4">
            <source src="${item.url}" type="video/mov">
            <!-- متن جایگزین در صورت عدم پشتیبانی مرورگر -->
            مرورگر شما از تگ ویدیو پشتیبانی نمی‌کند. لطفاً مرورگر خود را به‌روز کنید.
        </video>`;
        }

        if (isImg) {
            preview = `
            <div style="width: 100%;       
background-image: url('${item.url}');
background-size: contain; 
background-repeat: no-repeat;
background-position: center;" class="h-[200px] md:h-[200px] mb-1 md:mb-3">
            </div>
            `;
        }

        if (isAud) {
            preview = `
            <audio controls preload="auto" class="w-full">
            <!-- منابع صوتی در فرمت‌های مختلف برای پشتیبانی از همه مرورگرها -->
            <source src="${item.url}" type="audio/mpeg">
            <source src="${item.url}" type="audio/mp3">
            <source src="${item.url}" type="audio/ogg">
            <source src="${item.url}" type="audio/wav">
            متن جایگزین - مرورگر شما از تگ audio پشتیبانی نمی‌کند.
        </audio>
            `;
        }

        // قابل تغیر
        let alt = '';
        if(item.format == 'png' || item.format == 'jpg'){
            alt = `<form class="auto-form">
            <input type="text" name="value" class="input input-xs md:input-sm alt_image" value="${item.alt}" placeholder="متن جایگزین...">
            <input type="hidden" name="media_id" value="${item.id}">
            <input type="hidden" name="key" value="alt">
            </form>`;
        }

        $('#mm-sidebar-content').html(`
            ${preview}
            <div class="space-y-2 text-xs">
                <p class="font-medium text-sm break-all">${item.name}</p>
                <p><span class="badge badge-soft badge-primary badge-xs">${(item.format||'').toUpperCase()}</span></p>
                <p>حجم: ${sizeText}</p>
                ${item.width ? `<p>ابعاد: ${item.width} × ${item.height}</p>` : ''}
                <hr class="my-2">
                 ${alt}
                <a href="${item.url}" target="_blank"
                   class="btn btn-xs btn-soft w-full">
                    <span class="icon-[tabler--external-link] size-3"></span>
                    مشاهده فایل
                </a>
                <button type="button" id="mm-delete-btn"
                    class="btn btn-xs btn-error btn-soft w-full"
                    data-id="${item.id}">
                    <span class="icon-[tabler--trash] size-3"></span>
                    حذف
                </button>
            </div>
        `);

        $('#mm-sidebar').removeClass('hidden');
    }

    // ========== درج رسانه (قلب سیستم) ==========
    function doInsert() {
        const item = state.selectedItem;
        if (!item) return;

        const fmt = (item.format || '').toLowerCase();
        const isImg = CONFIG.imageExts.includes(fmt);
        const isVid = CONFIG.videoExts.includes(fmt);
        const isAud = CONFIG.audioExts.includes(fmt);

        // --- حالت input: پر کردن input های یه فیلد ---
        if (state.mode === 'input' && state.target) {
            const $container = $(state.target);

            // hidden input: مقدار URL
            $container.find('.mm-hidden-input').val(item.url);

            // پیش‌نمایش
            const $preview = $container.find('.mm-preview');
            if (isImg) {
                $preview.html(`
                    <div class="relative inline-block mt-2">
                        <img src="${item.url}" class="h-24 w-24 object-cover rounded-lg border-2 border-primary">
                        <button type="button" class="mm-clear-btn absolute -top-2 -right-2
                            btn btn-circle btn-xs btn-error">
                            <span class="icon-[tabler--x] size-3"></span>
                        </button>
                    </div>
                `);
            } else {
                $preview.html(`
                    <div class="relative inline-flex items-center gap-2 mt-2 p-2 bg-base-200 rounded-lg">
                        <span class="icon-[tabler--file] size-5 text-warning"></span>
                        <span class="text-sm">${item.name}</span>
                        <button type="button" class="mm-clear-btn btn btn-circle btn-xs btn-error mr-2">
                            <span class="icon-[tabler--x] size-3"></span>
                        </button>
                    </div>
                `);
            }
        }

        // --- حالت TinyMCE: درج در موقعیت cursor ---
        if (state.mode === 'tinymce' && state.editor) {
            let insertContent = '';

            if (isImg) {
                insertContent = `<img src="${item.url}" alt="${item.name}" style="max-width:100%;">`;
            } else if (isVid) {
                insertContent = `<video controls style="max-width:100%;">
                    <source src="${item.url}" type="video/${fmt}">
                </video>`;
            } else if (isAud) {
                insertContent = `<audio controls>
                    <source src="${item.url}" type="audio/${fmt}">
                </audio>`;
            } else {
                insertContent = `<a href="${item.url}" target="_blank">${item.name}</a>`;
            }

            state.editor.insertContent(insertContent);
        }

        // callback سفارشی
        if (typeof state.onSelect === 'function') {
            state.onSelect(item);
        }

        closeModal();
    }

    // ========== حذف رسانه ==========
    function deleteMedia(id) {
        $.ajax({
            url: CONFIG.deleteUrl + '/' + id,
            type: 'POST',
            data: { _method: 'delete' },
            success: function () {
                // حذف از state
                state.allItems = state.allItems.filter(i => i.id != id);
                renderGrid();
                $('#mm-sidebar').addClass('hidden');
                state.selectedItem = null;
                $('#mm-insert-btn').prop('disabled', true);
            },
            error: function () {
                alert('خطا در حذف فایل');
            }
        });
    }

    // ========== باز کردن مودال ==========
    function openModal(options) {
        options = options || {};

        // ریست state
        state.mode      = options.mode     || 'input';
        state.target    = options.target   || null;
        state.editor    = options.editor   || null;
        state.onSelect  = options.onSelect || null;
        state.filter    = 'all';
        state.search    = '';
        state.selectedItem = null;

        buildModal();

        // ریست UI
        $('#mm-search').val('');
        $('.mm-filter').removeClass('btn-primary active').addClass('btn-soft');
        $('.mm-filter[data-filter="all"]').removeClass('btn-soft').addClass('btn-primary active');
        $('#mm-insert-btn').prop('disabled', true);
        $('#mm-sidebar').addClass('hidden');
        $('.mm-item').removeClass('mm-selected');

        // برگشت به تب browse
        $('.mm-tab').removeClass('active');
        $('.mm-tab[data-tab="browse"]').addClass('active');
        // $('#mm-tab-browse').removeClass('hidden');
        $('#mm-tab-upload').addClass('!hidden');

        // نمایش مودال (FlyonUI overlay)
        const overlay = window.HSOverlay
            ? HSOverlay.getInstance('#mm-modal', true)
            : null;

        if (overlay) {
            overlay.open();
        } else {
            $('#mm-modal').removeClass('!hidden').addClass('open');
        }

        // لود گرید
        loadGrid();

        // راه‌اندازی FilePond اگر هنوز نشده
        initFilePond();
        closeDetail();
        
    }

    

    function closeDetail(){
                    $(document).on('click' , '#close_detail2' ,  function(){
    $('#mm-sidebar').removeClass('absolute').addClass('hidden');
});
    }

    // ========== بستن مودال ==========
    function closeModal() {
        const overlay = window.HSOverlay
            ? HSOverlay.getInstance('#mm-modal', true)
            : null;

        if (overlay) {
            overlay.close();
        } else {
            $('#mm-modal').addClass('!hidden').removeClass('open');
        }
    }

    // ========== راه‌اندازی FilePond ==========
    function initFilePond() {
        if (state.pond) return; // قبلاً init شده

        const el = document.querySelector('#mm-filepond');
        if (!el) return;

        state.pond = FilePond.create(el, {
            credits: false,
            allowMultiple: true,
            maxFiles: 20,
            labelIdle: 'فایل‌ها را اینجا بکشید یا <span class="filepond--label-action">انتخاب کنید</span>',
            server: {
                process: {
                    url: CONFIG.uploadUrl,
                    method: 'POST',
                    onload: (response) => response
                },
                revert: {
                    url: CONFIG.deleteUrl,
                    method: 'DELETE'
                }
            },
            onprocessfile: function (error, file) {
                if (!error) {
                    // بعد از آپلود state رو invalidate کن تا دفعه بعد reload بشه
                    state.allItems = [];
                }
            }
        });
    }

    // ========== Public API ==========
    window.MediaManager = {
        init: function (config) {
            CONFIG.listUrl   = config.listUrl;
            CONFIG.uploadUrl = config.uploadUrl;
            CONFIG.deleteUrl = config.deleteUrl;
            CONFIG.altUrl = config.altUrl;
        },
        open: function (options) {
            openModal(options);
        }
    };

    // ========== CSS اضافه ==========
    $('<style>')
        .text(`
            .mm-item.mm-selected {
                border-color: var(--color-primary) !important;
                box-shadow: 0 0 0 3px color-mix(in oklab, var(--color-primary) 30%, transparent);
            }
            @media (width >= 768px) {
            #mm-modal .modal-content {
                display: flex;
                flex-direction: column;
            }
            }

            @media (width <= 768px) {
            #mm-modal .modal-content {
            display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: stretch;
    align-content: stretch;
    flex-wrap: nowrap;
    }

    #mm-modal .modal-body{
    height: stretch !important;
    }

    #sidebar_mm2{
    top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
    }
    }


    .filepond--panel-root{
    background-color:#f3efff !important;
      position: relative;
  background: 
    /* خط بالایی */
    repeating-linear-gradient(to right, #854aff, #854aff 10px, transparent 10px, transparent 20px) top left / 100% 3px repeat-x,
    /* خط راست */
    repeating-linear-gradient(to bottom, #854aff, #854aff 10px, transparent 10px, transparent 20px) top right / 3px 100% repeat-y,
    /* خط پایینی */
    repeating-linear-gradient(to right, #854aff, #854aff 10px, transparent 10px, transparent 20px) bottom left / 100% 3px repeat-x,
    /* خط چپ */
    repeating-linear-gradient(to bottom, #854aff, #854aff 10px, transparent 10px, transparent 20px) top left / 3px 100% repeat-y;
  background-repeat: no-repeat;
    border-radius: 12px !important;
    }

@media (width >= 768px) {
            #mm-modal .modal-body {
                overflow: hidden;
            }
            }

            #mm-tab-browse {
                display: flex;
                flex-direction: column;
                overflow: hidden;
                flex: 1;
            }
        `)
        .appendTo('head');



})(jQuery);