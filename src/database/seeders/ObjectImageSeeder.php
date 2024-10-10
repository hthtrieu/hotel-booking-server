<?php

namespace Database\Seeders;

use App\Models\Hotels;
use App\Models\ObjectImages;
use App\Traits\FileUploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ObjectImageSeeder extends Seeder
{
    use FileUploader;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = Hotels::all();
        $images = [
            '01.jpg',
            '02.jpeg',
            '03.jpg',
            '04.jpg',
            '05.jpg',
            '06.jpg',
        ];

        foreach ($hotels as $hotel) {
            foreach ($images as $image) {
                // Tạo đường dẫn tuyệt đối đến tệp
                $filePath = storage_path('app/public/seeder/images/' . $image);

                // Tạo đối tượng UploadedFile từ đường dẫn tệp
                $file = new UploadedFile($filePath, $image, null, null, true);

                // Upload file
                $path = $this->uploadFile($file, 'images');  // Lưu tệp vào thư mục 'images'
                // Thêm bản ghi vào bảng ObjectImages
                ObjectImages::insert([
                    [
                        'id' => Str::uuid(),
                        'object_id' => $hotel->id,
                        'path' => $path,
                    ],
                ]);
            }
        }
    }
}
