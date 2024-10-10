<?php

namespace App\Services\Images;

use App\Traits\ResponseApi;
use Carbon\Carbon;
use App\Repositories\Image\ImageRepositoryInterface;

class ImageService implements ImageServiceInterface
{
    use ResponseApi;

    public function __construct(
        private readonly ImageRepositoryInterface $imageRepo,
    ) {}

    public function getHotelImages(string $hotelId)
    {
        $result = $this->imageRepo->getBy([
            'object_id' => $hotelId
        ]);
        return $result;
    }
}
