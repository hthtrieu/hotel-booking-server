<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use App\Traits\FileUploader;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client; // Import S3Client

class UploadController extends Controller
{
    use FileUploader, ResponseApi;

    public function getPresignedURL(Request $request)
    {
        $fileName = $request->file_name;
        $result = $this->genPresignedURL($fileName);
        return $result;
    }

    public function store(Request $request)
    {
        try {
            $image_url = $this->uploadFile($request->file('image'), 'image');
            if ($image_url) {
                return $this->respond([
                    'image_url' => $image_url
                ], "File uploaded successfully");
            } else {
                return $this->respondWithMessage("Failed to upload file", 400);
            }
        } catch (\Exception $e) {
            return $this->respondWithMessage("Failed to upload file: " . $e->getMessage(), 400);
        }
    }

    public function getImageURL(Request $request)
    {
        $path = $request->file_name; //example: hotel'images path
        $url = $this->getURL($path);
        return $this->respond([
            'url' => $url,
        ]);
    }
}
