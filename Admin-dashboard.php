<?php
session_start();
// Include database connection
include('config.php'); // This will include your database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch user data from database
    $sql = "SELECT id, name, email, role FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    // If the user is found
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $email, $role);
        $stmt->fetch();

        // Set session variables
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $role;

        // Redirect based on user role
        if ($role == 'Admin') {
            header("Location: Admin-dashboard.php");
        } elseif ($role == 'Admin') {
            header("Location: Regular_Admin.php");
        } else {
            // Redirect to a default page for regular users or unauthorized users
            header("Location: login.php");
        }
        exit();
    } else {
        // Invalid credentials
        $error_message = "Invalid email or password. Please try again.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Admin-dashboard.css"> <!-- Link to your CSS file -->
</head>
<body>

    <h2>Login to Your Account</h2>

    <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Sign up</a></p>

</body>
</html>
