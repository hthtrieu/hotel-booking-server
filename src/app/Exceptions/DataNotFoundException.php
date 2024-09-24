<?php

namespace App\Exceptions;

use Exception;

class DataNotFoundException extends Exception
{
    public function __construct($message = "Resource not found")
    {
        parent::__construct($message);
    }

    // Bạn có thể thêm phương thức render nếu muốn tùy chỉnh response
    public function render($request)
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], 404); // Trả về lỗi 404 khi không tìm thấy dữ liệu
    }
}
