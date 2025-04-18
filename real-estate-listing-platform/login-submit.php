<?php
include("./common.php");
session_start();

$errors = [];

if (!isset($_POST["username"]) || trim($_POST["username"]) == "") {
    $errors[] = "Please provide a username.";
}

if (!isset($_POST["password"]) || trim($_POST["password"]) == "") {
    $errors[] = "Please provide a password.";
}

if (!empty($errors)) {
    $_SESSION["status"] = "error";
    $_SESSION["errors"] = $errors;
    header("Location: ./login.php");
    exit();
} else {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Assuming authenticate_user function correctly verifies the user
    // Now, let's use a prepared statement for fetching user type
    $conn = connectToDatabase(); // Ensure this function properly initializes a database connection

    $stmt = $conn->prepare("SELECT user_type FROM USERS WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Assuming your authenticate_user function or equivalent logic here:
        if (authenticate_user($username, $password)) {
            $_SESSION["user"] = $username; // Store username in session
            $_SESSION["user_type"] = $row["user_type"]; // Store user type in session

            header("Location: ./dashboard.php");
            exit();
            
        } else {
            // Handle incorrect password scenario
            $_SESSION["status"] = "error";
            $_SESSION["errors"] = ["Incorrect username or password."];
            header("Location: ./login.php");
            exit();
        }
    } else {
        // Handle user not found scenario
        $_SESSION["status"] = "error";
        $_SESSION["errors"] = ["User not found."];
        header("Location: ./login.php");
        exit();
    }
}
?>