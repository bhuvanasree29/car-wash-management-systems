<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Connect to database
    $conn = new mysqli("localhost", "root", "", "car_wash_signup");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM users1 WHERE name = ?");
    $stmt->bind_param("s", $name);

    // Execute the statement
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $hashed_password)) {
        // Redirect to home.html after successful login
        header("Location: http://localhost/practise/Home1.html");
        exit(); // Stop further script execution
    } else {
        echo "Invalid name or password!";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
