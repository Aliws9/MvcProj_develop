<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MediaMetaRequest;
use App\Http\Requests\Admin\MediaRequest;
use App\Media;
use App\MediaMeta;

class MediaController extends AdminController
    {
    public function index()
        {
        $medias = Media::all();
        return view('admin.media.index', compact('medias'));
        }

    public function create()
        {
        return view('admin.media.create');
        }

    public function altImage()
        {


        $altImage = isset($_POST['value']) ? $_POST['value'] : '';
        $idAltImage = isset($_POST['media_id']) ? $_POST['media_id'] : '';



        $mm = new MediaMeta();
        $r = $mm::where('media_id', $idAltImage)->get();

        if ($r == NULL) {
            $request = new MediaMetaRequest();
            $inputs = $request->all();

            if ($mm::create($inputs)) {

                }
            }
        else {

            $request = new MediaMetaRequest();
            $inputs = $request->all();

            $this->updateAlt($idAltImage, $inputs);
            }

        }

    public function updateAlt($id, $inputs)
        {
        $mm = new MediaMeta();
        $mm::updateWhere(['media_id' => $id], $inputs);
        }

    /**
     * آپلود فایل توسط FilePond
     * FilePond یه POST می‌فرسته و انتظار داره یه string (شناسه) برگرده
     */
    public function store()
        {
        if (!isset($_FILES['filepond'])) {
            http_response_code(400);
            echo json_encode(['error' => 'No file']);
            exit;
            }

        $file = $_FILES['filepond'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            exit;
            }

        // ساخت مسیر آپلود
        $datePath = date('Y/m');
        $uploadDir = dirname(__DIR__, 4) . '/public/upload/' . $datePath;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
            }

        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $uniqueName = uniqid() . '_' . time() . '.' . $ext;
        $destination = $uploadDir . '/' . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            http_response_code(500);
            exit;
            }

        // اطلاعات تصویر
        $width = $height = null;
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            [$width, $height] = getimagesize($destination);
            }

        $relativePath = 'upload/' . $datePath . '/' . $uniqueName;
        $fullUrl = asset($relativePath);

        // ذخیره در دیتابیس
        $media = new Media();
        Media::create([
            'file_name' => $file['name'],
            'path'      => $relativePath,
            'url'       => $fullUrl,
            'format'    => $ext,
            'mime_type' => $file['type'],
            'size'      => $file['size'],
            'width'     => $width,
            'height'    => $height,
            'user_id'   => 1, // یا Auth::user()->id
        ]);

        // FilePond انتظار داره یه serverId برگرده (برای حذف بعداً)
        $insertedId = \System\Database\DBConnection\DBConnection::newInsertId();

        header('Content-Type: text/plain');
        echo $insertedId; // این ID رو FilePond نگه می‌داره
        exit;
        }

    /**
     * حذف فایل - FilePond یه DELETE می‌فرسته با serverId
     */
    public function destroy($id)
        {
        $media = Media::find($id);
        if ($media) {
            // حذف فایل فیزیکی (اختیاری)
            $filePath = dirname(__DIR__, 4) . '/public/' . $media->path;
            if (file_exists($filePath)) {
                unlink($filePath);
                }
            Media::delete($id);
            }
        http_response_code(200);
        exit;
        }

    /**
     * دریافت لیست رسانه‌ها به صورت JSON (برای AJAX)
     */
    public function getList()
        {
        $medias = Media::all();
        $list = [];

        foreach ($medias as $media) {
            $altValue = '';

            // get() آرایه برمیگردونه، نه null — باید count چک بشه
            $altResults = MediaMeta::where('media_id', $media->id)
                                   ->where('key', 'alt')
                                   ->get();

            if (!empty($altResults) && count($altResults) > 0) {
                // آرایه‌ست، پس باید index بزنیم
                $altValue = $altResults[0]->value ?? '';
                }

            $list[] = [
                'id'        => $media->id,
                'file_name' => $media->file_name,
                'url'       => $media->url,
                'format'    => $media->format,
                'size'      => $media->size,
                'width'     => $media->width,
                'height'    => $media->height,
                'alt'       => $altValue,
            ];
            }

        header('Content-Type: application/json');
        echo json_encode($list);
        exit;
        }
    }