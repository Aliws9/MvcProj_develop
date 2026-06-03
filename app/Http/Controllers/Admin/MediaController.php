<?php

namespace App\Http\Controllers\Admin;

use App\Media;

use App\Http\Requests\Admin\MediaRequest;

class MediaController extends AdminController{
    public function index(){
        
    }
    public function create(){
    }

    public function store()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo 'Method Not Allowed';
        exit;
    }

    if (!isset($_FILES['filepond'])) {
        http_response_code(400);
        echo 'No file uploaded';
        exit;
    }

    $file = $_FILES['filepond'];

    if (!isset($file['error']) || is_array($file['error'])) {
        http_response_code(400);
        echo 'Invalid upload';
        exit;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo 'Upload failed';
        exit;
    }

    $rootPath = dirname(__DIR__, 4) . '/public/upload';
    $datePath = date('Y/m');
    $targetDir = $rootPath . '/' . $datePath;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $originalName = $file['name'];
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $filename = uniqid('media_', true) . ($ext ? '.' . $ext : '');

    $destination = $targetDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        http_response_code(500);
        echo 'Could not move file';
        exit;
    }

    // این مسیر را بر اساس فرانت خودت برگردان
    // اگر URL تو این است: localhost:8000/public/upload
    // همین را برگردان:
    $relativePath = 'public/upload/' . $datePath . '/' . $filename;

    header('Content-Type: text/plain; charset=utf-8');
    echo $relativePath;
    exit;
}

    public function update(){

    }
    public function edit(){

    }
    public function destroy(){

    }
}