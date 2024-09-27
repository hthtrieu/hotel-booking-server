<?php

namespace App\Repositories\Invoice;

use App\Repositories\BaseRepositoryInterface;
use App\Models\Invoice;

interface IInvoiceRepository extends BaseRepositoryInterface
{

    public function insertInvoice($data);
}
