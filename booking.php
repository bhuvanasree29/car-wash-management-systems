<?php
$host = 'localhost';
$db = 'car_wash_signup';
$user = '*******';
$pass = '*******';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id= $_POST['userID'];
    $fullName = $_POST['fullName'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $vehicleType = $_POST['vehicleType'];
    $schedule = $_POST['schedule'];
    $timeSlot = $_POST['timeSlot'];
    $totalPrice = $_POST['totalPrice'];

    // Insert booking details into database
    $sql = "INSERT INTO bookings (id, full_name, contact, email, vehicle_type, schedule, time_slot, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssss", $id, $fullName, $contact, $email, $vehicleType, $schedule, $timeSlot, $totalPrice);
        if ($stmt->execute()) {
            echo "<script>alert('Booking successful!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>
