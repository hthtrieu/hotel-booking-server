<?php

namespace App\Models;

use App\Traits\UserActionTracking;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasUuids;
    use UserActionTracking;
}
