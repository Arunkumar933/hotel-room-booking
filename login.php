<?php
// Start session
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['username']);
    $user_password = trim($_POST['password']);

    // Prepare and bind the query
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($user_password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to booking_confirmation.html or another page
            header("Location: booking_confirmation.html");
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No such user found!";
    }

    $stmt->close();
    $conn->close();
}
?>
