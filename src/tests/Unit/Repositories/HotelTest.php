<?php

namespace Tests\Unit\Repositories;

use App\Models\Hotels;
use App\Repositories\Hotel\HotelRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelTest extends TestCase
{
    use RefreshDatabase;

    protected $hotelRepo;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed dữ liệu cho database
        $this->seed();

        // Khởi tạo đối tượng HotelRepo
        $this->hotelRepo = new HotelRepo();
    }

    public function test_get_hotels_list_with_valid_filters()
    {
        // Thiết lập các bộ lọc
        $filters = [
            'province' => 'Thành phố Đà Nẵng',
            'rooms' => 3,
            'checkin' => '2024-09-11',
            'checkout' => '2024-09-12',
            'adult' => 2,
            'children' => 0,
        ];

        // Gọi phương thức cần test với dữ liệu thật
        $result = $this->hotelRepo->getHotelsList($filters);

        // Kiểm tra kết quả trả về (Assertions)
        $this->assertIsArray($result);
        $this->assertNotEmpty($result); // Đảm bảo có kết quả trả về từ database
        // // Kiểm tra các phần tử trong mảng có phải là đối tượng Hotels không
        foreach ($result as $hotel) {
            $this->assertInstanceOf(Hotels::class, $hotel['hotel']);
        }
    }
}
