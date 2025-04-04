<?php
// Connection to MySQL database
$servername = "localhost"; // Database server
$username = "root"; // Database username (default for localhost is "root")
$password = ""; // Database password (default for localhost is empty)
$dbname = "user_db"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    $user_name = htmlspecialchars($_POST['username']);
    $user_email = htmlspecialchars($_POST['email']);
    $user_password = htmlspecialchars($_POST['password']); // Password entered by the user

    // Basic validation
    if (empty($user_name) || empty($user_email) || empty($user_password)) {
        echo "All fields are required.";
    } else {
        // Hash the password using password_hash() before storing it
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT); // Hash the password

        // Prepare SQL statement to insert data with hashed password
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_name, $user_email, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header("Location: login.html"); // Ensure login.html path is correct
            exit; // Stop further script execution after the redirect
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
