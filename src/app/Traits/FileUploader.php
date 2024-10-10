<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploader
{
    public function uploadFile(UploadedFile $file, $folder = null, $disk = 's3', $fileName = null)
    {
        $folder = !is_null($folder) ? $folder : 'default-folder';
        $fileName = !is_null($fileName) ? $fileName : Str::random(10) . '.' . $file->getClientOriginalExtension();

        try {
            $fileContent = file_get_contents($file->getRealPath());
            $path = Storage::disk($disk)->put($folder . '/' . $fileName, $fileContent);

            if ($path) {
                // return Storage::disk($disk)->url($folder . '/' . $fileName);
                return $folder . '/' . $fileName;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            dd($e);
            return false;
        }
    }


    public function deleteFile($path, $disk = 's3')
    {
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    public function genPresignedURL($fileName = null, $folder = 'images')
    {
        $fileName = !is_null($fileName) ? $fileName : Str::random(10);
        $path = trim($folder, '/') . '/' . $fileName; // loại bỏ gạch chéo dư thừa
        // $filePath = $folder . $fileName; // Đường dẫn đầy đủ với folder

        $client = Storage::disk('s3')->getClient();
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $path, // Sử dụng đường dẫn đầy đủ
        ]);

        $presignedRequest = $client->createPresignedRequest($command, '+20 minutes');
        return [
            'file_name' => $path,
            'pre_signed' => (string) $presignedRequest->getUri(),
        ];
    }

    public function getURL($path)
    {
        $client = Storage::disk('s3')->getClient();
        $command = $client->getCommand('GetObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $path,
        ]);
        $presignedRequest = $client->createPresignedRequest($command, '+60 minutes');
        return (string) $presignedRequest->getUri();
    }
}
