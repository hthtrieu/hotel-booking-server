<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUploader
{
    public function uploadFile(UploadedFile $file, $folder = null, $disk = 's3', $fileName = null)
    {
        $fileName = ! is_null($fileName) ? $fileName : Str::random(10) . '.' . $file->getClientOriginalExtension();

        try {
            // return $file->storeAs($folder, $fileName, $disk);
            $fileContent = file_get_contents($file->getRealPath());
            $path =  Storage::disk($disk)->put($folder . '/' . $fileName, $fileContent);
            // if ($path) {
            //     $url = Storage::disk($disk)->url($folder . '/' . $fileName, $fileContent);
            //     return $url;
            // }
            if (method_exists(Storage::disk($disk), 'url') && $path) {
                $url = Storage::disk($disk)->url($folder . '/' . $fileName);
                return $url;
            } else {
                // \Log::error('The url method does not exist for the disk: ' . $disk);
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteFile($path, $disk = 's3')
    {
        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
