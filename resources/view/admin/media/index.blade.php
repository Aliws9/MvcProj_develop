@extends('admin.layouts.app')

@section('head-tag')
<title>ادمین | مدیر رسانه</title>
<script src="<?= asset('jquery/dist/jquery.min.js'); ?>"></script>
<script src="<?= asset('flyonui/flyonui.js'); ?>"></script>
<link rel="stylesheet" href="<?= asset('filepond/dist/filepond.min.css'); ?>">
<style>
    /* گرید رسانه‌ها */
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
        padding: 16px;
    }

    .media-item {
        position: relative;
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 8px;
        overflow: hidden;
        transition: border-color 0.2s;
        aspect-ratio: 1;
        background: #f1f5f9;
    }

    .media-item:hover {
        border-color: var(--color-primary);
    }

    .media-item.selected {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px color-mix(in oklab, var(--color-primary) 30%, transparent);
    }

    .media-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-item .file-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        gap: 8px;
        padding: 12px;
        text-align: center;
    }

    .media-item .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: opacity 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .media-item:hover .overlay {
        opacity: 1;
    }

    /* سایدبار جزئیات */
    .media-sidebar {
        width: 280px;
        min-width: 280px;
        border-right: 1px solid var(--color-base-content);
        padding: 16px;
        overflow-y: auto;
        display: none;
    }

    .media-sidebar.active {
        display: block;
    }
</style>
@endsection

@section('content')

<div class="w-full flex flex-col gap-4">

    <!-- نوار ابزار -->
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <h1 class="text-xl font-semibold">مدیر رسانه</h1>
        <div class="flex gap-2">
            <!-- دکمه آپلود -->
            <button type="button" id="open-upload-modal"
                class="btn btn-primary btn-sm lg:btn-md"
                aria-haspopup="dialog" aria-expanded="false"
                aria-controls="upload-modal" data-overlay="#upload-modal">
                <span class="icon-[tabler--upload] size-5"></span>
                آپلود فایل
            </button>
        </div>
    </div>

    <!-- فیلترها و جستجو -->
    <div class="flex flex-wrap gap-3 items-center bg-white p-3 rounded-lg shadow-sm">
        <div class="input input-sm max-w-60">
            <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
            <input type="search" class="grow" placeholder="جستجو..." id="media-search" />
        </div>
        <div class="flex gap-2">
            <button class="btn btn-sm btn-soft filter-btn active" data-filter="all">همه</button>
            <button class="btn btn-sm btn-soft filter-btn" data-filter="image">تصاویر</button>
            <button class="btn btn-sm btn-soft filter-btn" data-filter="video">ویدیو</button>
            <button class="btn btn-sm btn-soft filter-btn" data-filter="audio">صدا</button>
            <button class="btn btn-sm btn-soft filter-btn" data-filter="other">سایر</button>
        </div>
    </div>

    <!-- محتوای اصلی -->
    <div class="bg-white rounded-lg shadow-sm flex overflow-hidden" style="min-height: 500px;">

        <!-- گرید رسانه‌ها -->
        <div class="flex-1 overflow-y-auto">
            <div class="media-grid" id="media-grid">
                <!-- آیتم‌ها از AJAX لود می‌شن -->
                <div class="col-span-full flex justify-center items-center py-20">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                </div>
            </div>
        </div>

        <!-- سایدبار جزئیات -->
        <div class="media-sidebar" id="media-sidebar">
            <h3 class="font-semibold mb-3">جزئیات فایل</h3>
            <div id="sidebar-content">
                <!-- اطلاعات فایل انتخاب شده اینجا نمایش داده می‌شه -->
            </div>
        </div>

    </div>

</div>

<!-- مودال آپلود -->
<div id="upload-modal"
    class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle hidden"
    role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">آپلود فایل جدید</h3>
                <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3"
                    aria-label="Close" data-overlay="#upload-modal">
                    <span class="icon-[tabler--x] size-4"></span>
                </button>
            </div>
            <div class="modal-body">
                <input type="file" id="upload-pond" name="filepond" multiple
                    accept="image/*,video/*,audio/*,.pdf,.doc,.docx">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft btn-secondary" data-overlay="#upload-modal">
                    بستن
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Template آیتم رسانه -->
<template id="media-item-template">
    <div class="media-item" data-id="" data-format="" data-url="" data-name="" data-size="">
        <!-- محتوا اینجا inject می‌شه -->
        <div class="overlay">
            <button type="button" class="btn btn-circle btn-sm btn-info btn-detail"
                title="جزئیات">
                <span class="icon-[tabler--info-circle] size-4"></span>
            </button>
            <button type="button" class="btn btn-circle btn-sm btn-error btn-delete-media"
                title="حذف">
                <span class="icon-[tabler--trash] size-4"></span>
            </button>
        </div>
    </div>
</template>


<script>
$(document).ready(function () {

    // ===== ۱. مقداردهی FilePond =====
    FilePond.registerPlugin(FilePondPluginImagePreview);

    const pond = FilePond.create(document.querySelector('#upload-pond'), {
        credits: false,
        allowMultiple: true,
        maxFiles: 20,
        labelIdle: 'فایل‌ها را اینجا رها کنید یا <span class="filepond--label-action">انتخاب کنید</span>',
        server: {
            process: {
                url: "<?= route('admin.media.store') ?>",
                method: 'POST',
                onload: (response) => response // response همون ID هست
            },
            revert: {
                url: "<?= url('admin/media/delete') ?>/",
                method: 'DELETE'
            }
        },
        onprocessfile: (error, file) => {
            if (!error) {
                // بعد از آپلود، گرید رو reload کن
                loadMediaGrid();
            }
        }
    });

    // ===== ۲. لود گرید رسانه‌ها =====
    function loadMediaGrid(filter = 'all', search = '') {
        $('#media-grid').html(`
            <div class="flex justify-center items-center py-20 col-span-full">
                <span class="loading loading-spinner loading-lg text-primary"></span>
            </div>
        `);

        $.ajax({
            url: "<?= route('admin.media.list') ?>",
            type: 'GET',
            success: function (items) {
                renderGrid(items, filter, search);
            },
            error: function () {
                $('#media-grid').html('<p class="text-error p-4">خطا در بارگذاری</p>');
            }
        });
    }

    // ===== ۳. رندر گرید =====
    function renderGrid(items, filter = 'all', search = '') {
        const grid = $('#media-grid');
        grid.empty();

        const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        const videoExts = ['mp4', 'webm', 'mov', 'avi'];
        const audioExts = ['mp3', 'wav', 'ogg'];

        const filtered = items.filter(item => {
            const fmt = item.format.toLowerCase();
            const matchSearch = !search || item.file_name.toLowerCase().includes(search.toLowerCase());
            let matchFilter = true;

            if (filter === 'image') matchFilter = imageExts.includes(fmt);
            else if (filter === 'video') matchFilter = videoExts.includes(fmt);
            else if (filter === 'audio') matchFilter = audioExts.includes(fmt);
            else if (filter === 'other') matchFilter = !imageExts.includes(fmt) && !videoExts.includes(fmt) && !audioExts.includes(fmt);

            return matchSearch && matchFilter;
        });

        if (filtered.length === 0) {
            grid.html('<div class="col-span-full text-center py-20 text-base-content/50">فایلی یافت نشد</div>');
            return;
        }

        filtered.forEach(item => {
            const fmt = item.format.toLowerCase();
            const isImage = imageExts.includes(fmt);
            const isVideo = videoExts.includes(fmt);
            const isAudio = audioExts.includes(fmt);

            let content = '';
            if (isImage) {
                content = `<img src="${item.url}" alt="${item.file_name}" loading="lazy">`;
            } else if (isVideo) {
                content = `
                    <div class="file-icon">
                        <span class="icon-[tabler--video] size-10 text-primary"></span>
                        <span class="text-xs truncate w-full text-center">${item.file_name}</span>
                    </div>`;
            } else if (isAudio) {
                content = `
                    <div class="file-icon">
                        <span class="icon-[tabler--music] size-10 text-success"></span>
                        <span class="text-xs truncate w-full text-center">${item.file_name}</span>
                    </div>`;
            } else {
                content = `
                    <div class="file-icon">
                        <span class="icon-[tabler--file] size-10 text-warning"></span>
                        <span class="text-xs truncate w-full text-center">${item.file_name}</span>
                    </div>`;
            }

            const itemEl = $(`
                <div class="media-item"
                    data-id="${item.id}"
                    data-format="${item.format}"
                    data-url="${item.url}"
                    data-name="${item.file_name}"
                    data-size="${item.size}"
                    data-width="${item.width || ''}"
                    data-height="${item.height || ''}">
                    ${content}
                    <div class="overlay">
                        <button type="button" class="btn btn-circle btn-sm btn-info btn-detail" title="جزئیات">
                            <span class="icon-[tabler--info-circle] size-4"></span>
                        </button>
                        <button type="button" class="btn btn-circle btn-sm btn-error btn-delete-media" title="حذف">
                            <span class="icon-[tabler--trash] size-4"></span>
                        </button>
                    </div>
                </div>
            `);

            grid.append(itemEl);
        });
    }

    // ===== ۴. نمایش جزئیات در سایدبار =====
    $(document).on('click', '.btn-detail', function (e) {
        e.stopPropagation();
        const item = $(this).closest('.media-item');
        
        // mark selected
        $('.media-item').removeClass('selected');
        item.addClass('selected');

        const data = item.data();
        const sizeKB = (data.size / 1024).toFixed(1);
        const sizeMB = (data.size / 1024 / 1024).toFixed(2);
        const displaySize = data.size > 1024*1024 ? sizeMB + ' MB' : sizeKB + ' KB';

        let preview = '';
        const imageExts = ['jpg','jpeg','png','gif','webp'];
        if (imageExts.includes(data.format.toLowerCase())) {
            preview = `<img src="${data.url}" class="w-full rounded-lg mb-3 object-cover">`;
        }

        $('#sidebar-content').html(`
            ${preview}
            <div class="space-y-2 text-sm">
                <div><span class="font-medium">نام فایل:</span><br>
                    <span class="text-base-content/70 break-all">${data.name}</span></div>
                <div><span class="font-medium">فرمت:</span>
                    <span class="badge badge-soft badge-primary badge-sm mr-1">${data.format.toUpperCase()}</span></div>
                <div><span class="font-medium">حجم:</span> ${displaySize}</div>
                ${data.width ? `<div><span class="font-medium">ابعاد:</span> ${data.width} × ${data.height}</div>` : ''}
                <div class="pt-2 border-t flex flex-col gap-2">
                    <a href="${data.url}" target="_blank" class="btn btn-sm btn-soft">
                        <span class="icon-[tabler--external-link] size-4"></span>
                        مشاهده فایل
                    </a>
                    <button type="button" class="btn btn-sm btn-soft btn-error btn-delete-from-sidebar"
                        data-id="${data.id}">
                        <span class="icon-[tabler--trash] size-4"></span>
                        حذف
                    </button>
                </div>
            </div>
        `);

        $('#media-sidebar').addClass('active');
    });

    // ===== ۵. حذف رسانه =====
    function deleteMedia(id) {
        if (!confirm('آیا مطمئن هستید؟')) return;

        $.ajax({
            url: "<?= url('admin/media/delete') ?>/" + id,
            type: 'POST',
            data: { _method: 'delete' },
            success: function () {
                $(`.media-item[data-id="${id}"]`).fadeOut(300, function () {
                    $(this).remove();
                });
                $('#media-sidebar').removeClass('active');
            },
            error: function () {
                alert('خطا در حذف فایل');
            }
        });
    }

    $(document).on('click', '.btn-delete-media', function (e) {
        e.stopPropagation();
        const id = $(this).closest('.media-item').data('id');
        deleteMedia(id);
    });

    $(document).on('click', '.btn-delete-from-sidebar', function () {
        deleteMedia($(this).data('id'));
    });

    // ===== ۶. فیلتر =====
    let currentFilter = 'all';
    let currentSearch = '';

    $(document).on('click', '.filter-btn', function () {
        $('.filter-btn').removeClass('active btn-primary').addClass('btn-soft');
        $(this).removeClass('btn-soft').addClass('btn-primary active');
        currentFilter = $(this).data('filter');
        loadMediaGrid(currentFilter, currentSearch);
    });

    // ===== ۷. جستجو =====
    let searchTimeout;
    $('#media-search').on('input', function () {
        clearTimeout(searchTimeout);
        currentSearch = $(this).val();
        searchTimeout = setTimeout(() => {
            loadMediaGrid(currentFilter, currentSearch);
        }, 400);
    });

    // ===== ۸. بستن سایدبار با کلیک بیرون =====
    $(document).on('click', '#media-grid', function (e) {
        if (!$(e.target).closest('.media-item').length) {
            $('#media-sidebar').removeClass('active');
            $('.media-item').removeClass('selected');
        }
    });

    // ===== لود اولیه =====
    loadMediaGrid();
});
</script>

<script src="<?= asset('filepond/dist/filepond.min.js') ?>"></script>
<link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css">
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

@endsection