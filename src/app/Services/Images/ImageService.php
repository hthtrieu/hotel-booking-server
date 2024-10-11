<?php

namespace App\Services\Images;

use App\Traits\ResponseApi;
use Carbon\Carbon;
use App\Repositories\Image\ImageRepositoryInterface;
use App\Traits\FileUploader;

class ImageService implements ImageServiceInterface
{
    use ResponseApi, FileUploader;

    public function __construct(
        private readonly ImageRepositoryInterface $imageRepo,
    ) {}

    public function getObjectImages(string $id)
    {
        $result = $this->imageRepo->getBy([
            'object_id' => $id
        ]);
        foreach ($result as $image) {
            $image->path = $this->getURL($image->path);
        }
        return $result;
    }
}
