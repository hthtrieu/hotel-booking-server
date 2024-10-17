<?php

namespace App\Http\Controllers\Reservation;

use App\Http\Controllers\Controller;
use App\Services\Reservation\IReservationService;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    use ResponseApi;
    public function __construct(
        private readonly IReservationService $reservationService,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * save the order
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->respond($this->reservationService->getInvoiceByReservationId($id));
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
