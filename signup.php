<?php
// Database credentials
$host = 'localhost';
$db = 'car_wash_signup';
$user = '********';
$pass = '********';

// Create connection to the MySQL database using mysqli
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the form data into the database
    $sql = "INSERT INTO users1 (id, name, phone, email, dob, gender, address, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssss", $id, $name, $phone, $email, $dob, $gender, $address, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Send back the user ID to the frontend using a GET parameter
            echo "<script>
                alert('Please note your User ID for future reference: ' + '$id');
                window.location.href = 'Login.html';
            </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
