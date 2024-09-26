<?php

namespace App\Repositories\Reservation;

use App\Enums\ReservationStatusEnum;
use App\Repositories\BaseRepository;
use App\Models\Reservation;
use Illuminate\Support\Str;

class ReservationRepository extends BaseRepository implements IReservationRepository
{
    protected $modelName = Reservation::class;

    public function createNewReservation($data)
    {
        $newReservation = new Reservation;
        $newReservation->email = $data['email'];
        $newReservation->site_fees = ($data['total_price'] * 20 / 100);
        $newReservation->tax_paid = $data['tax_paid'];
        $newReservation->status = ReservationStatusEnum::PENDING->value;
        $newReservation->total_price = $data['total_price'];
        $newReservation->user()->associate($data['user']); // Gắn khóa ngoại user_id

        // Lưu reservation trước
        $newReservation->save();


        // Danh sách các phòng (Room IDs) mà bạn muốn thêm vào bảng pivot
        $rooms = $data['rooms'];
        // Gán các phòng với các giá trị cho pivot 'start_day' và 'end_day'
        foreach ($rooms as $room) {
            // dd($room['id']);
            // Kiểm tra room id và ngày tháng trước khi gán
            // if (isset($room['id'], $data['start_day'], $data['end_day'])) {
            // }
            $newReservation->rooms()->attach($room['id'], [
                'start_day' => $data['start_day'], // Thay $data['start_day'] bằng $room['start_day']
                'end_day' => $data['end_day'],     // Thay $data['end_day'] bằng $room['end_day']
                'id' => Str::uuid(),
            ]);
        }

        return $newReservation;
    }
}
