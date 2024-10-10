<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use App\Traits\FileUploader;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    use FileUploader, ResponseApi;
    /**
     * Display a listing of the resource.
     */

    // public function getPreSigned(Request $request)
    // {
    //     $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
    //     $fileName = Str::random(10) . '_' . $request->file_name;
    //     $filePath = config('define.upload_path_zip_file') . '/' . $fileName;

    //     $command = $client->getCommand('PutObject', [
    //         'Bucket' => config('filesystems.disks.s3.bucket'),
    //         'Key' => $filePath,
    //     ]);

    //     $request = $client->createPresignedRequest($command, '+20 minutes');

    //     return [
    //         'file_path' => $filePath,
    //         'pre_signed' => (string) $request->getUri(),
    //     ];
    // }

    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
