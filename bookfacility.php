<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "facilitydb"); // Connect to the facility database

if (isset($_GET['facility_id'])) {
    $facility_id = $_GET['facility_id'];

    // Fetch facility details
    $query = "SELECT * FROM facilities WHERE facility_id = '$facility_id'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $facility = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Facility not found!'); window.location.href = 'facility.php';</script>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $flatno = $_POST['flatno'];
    $booking_name = $_POST['booking_name'];
    $booking_date = $_POST['booking_date'];

    // Check user credentials in the usersregister database
    $user_connection = mysqli_connect("localhost", "root", "", "usersregister");
    $user_query = "SELECT * FROM registration WHERE email = '$email' AND flatno = '$flatno'";
    $user_result = mysqli_query($user_connection, $user_query);

    if (mysqli_num_rows($user_result) > 0) {
        // Connect to bookfacilityDB database
        $booking_connection = mysqli_connect("localhost", "root", "", "bookingDB");

        // Check if the facility is already booked for the selected date
        $check_query = "SELECT * FROM bookings WHERE facility_id = '$facility_id' AND booking_date = '$booking_date'";
        $check_result = mysqli_query($booking_connection, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('The facility \"" . $facility['facility_name'] . "\" is already booked for the selected date. Please choose another date.');</script>";
        } else {
            // Insert booking into bookings table
            $insert_query = "INSERT INTO bookings (facility_id, booking_name, email, flatno, booking_date) VALUES ('$facility_id', '$booking_name', '$email', '$flatno', '$booking_date')";

            if (mysqli_query($booking_connection, $insert_query)) {
                echo "<script>alert('Booking confirmed!'); window.location.href = 'facility.php';</script>";
            } else {
                echo "<script>alert('Error saving booking!');</script>";
            }
        }
    } else {
        echo "<script>alert('User does not exist in our database!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Facility</title>
    <link rel="stylesheet" href="dashstyle.css">
    <style>
        .container {
            margin: 20px auto;
            width: 50%;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            background: #f9f9f9;
        }

        .facility-info {
            margin-bottom: 20px;
        }

        .facility-info h2, .facility-info p {
            margin: 10px 0;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin-bottom: 5px;
        }

        form input, form select {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            padding: 10px;
            background-color: #19B3D3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #148a9d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="facility-info">
            <h2>Facility: <?php echo $facility['facility_name']; ?></h2>
            <p><strong>Description:</strong> <?php echo $facility['description']; ?></p>
            <p><strong>Availability:</strong> <?php echo $facility['availability']; ?></p>
            <p><strong>Price:</strong> <?php echo $facility['price']; ?></p>
        </div>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="flatno">Flat Number:</label>
            <input type="text" id="flatno" name="flatno" required>

            <label for="booking_name">Booking Under Name:</label>
            <input type="text" id="booking_name" name="booking_name" required>

            <label for="booking_date">Booking Date:</label>
            <input type="date" id="booking_date" name="booking_date" required>

            <button type="submit" class="btn">Book now</button>
        </form>
    </div>
</body>
</html>
