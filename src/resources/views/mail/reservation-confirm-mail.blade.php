<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
        }
        .details {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .details p {
            margin: 0;
            padding: 5px 0;
        }
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reservation Confirmation</h1>
        <p>Dear {{ $user->name }},</p>
        <p>Thank you for booking with us! Here are your reservation details:</p>

        <div class="details">
            <p><strong>Reservation Code:</strong> {{ $reservation->reservation_code }}</p>
            <p><strong>Hotel:</strong> {{ $hotel->name }}</p>
            <p><strong>Check-in:</strong> {{ $reservation->checkin }}</p>
            <p><strong>Check-out:</strong> {{ $reservation->checkout }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Phone:</strong> {{ $user->phone_number }}</p>
            <p><strong>Phone:</strong> {{ $user->email }}</p>
            <p><strong>Total Nights:</strong> {{ $reservation->night_count}}</p>
            <p><strong>Total Price:</strong> ${{ number_format($reservation->total_price, 2) }}</p>

        </div>
 <h2>Booked Room Types</h2>
        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Number of Rooms</th>
                    <th>Price per Night</th>
                    <th>Total Nights</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($room_types as $roomType)
                    <tr>
                        <td>{{ $roomType->name }}</td>
                        <td>{{ $roomType->name }}</td>
                        <td>${{ number_format($roomType->price, 2) }}</td>
                        <td>{{ $roomType->days_count }}</td>
                        <td>${{ number_format($roomType->total_rooms * $roomType->price * $roomType->days_count, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>We look forward to welcoming you!</p>

        <div class="footer">
            <p>If you have any questions, feel free to contact our support team.</p>
            <p>Thank you for choosing our service!</p>
        </div>
    </div>
</body>
</html>
