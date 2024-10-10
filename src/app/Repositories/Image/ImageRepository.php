<?php

namespace App\Repositories\Image;

use App\Helpers\DayTimeHelper;
use App\Models\ObjectImages;
use App\Models\PaymentTypes;
use App\Models\Room;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{

    protected $modelName = ObjectImages::class;
}
