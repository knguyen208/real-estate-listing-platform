<?php
include("./common.php");
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'buyer') {
    header("Location: login.php");
    exit();
}

$propertyId = $_GET['id'] ?? null;

if (!$propertyId) {
    echo "Property ID is missing!";
    header("Location: buy.php");
    exit();
}

$conn = connectToDatabase();
$property = getProperty($propertyId);

function getProperty($id) {
    global $conn; 
    $query = "SELECT title, address, price FROM properties WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        return null;  
    }
    return $result->fetch_assoc();
}

$conn->close();

if (!$property) {
    echo "No property found with ID: $propertyId";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap.css" rel="stylesheet">
    <link href="./mt4.css" rel="stylesheet">
    <title>Congratulations</title>
</head>
<body>
    <div class="topnav">
        <a href="./mt4.php">Home</a>
        <a href="./wishlist.php">Wishlist</a>
        <a href="./buy.php">Buy</a>
        <a href="./login.php?logout=true">Log Out</a>
    </div>
    <div class="container">
        <h1>Congratulations on Your New Home!</h1>
        <h2><?= htmlspecialchars($property['title']) ?></h2>
        <p>You are now the proud owner of a property at <?= htmlspecialchars($property['address']) ?>.</p>
        <p>The purchase price was $<?= number_format($property['price']) ?>.</p>
        <p>Thank you for choosing our service. If you have any questions or need further assistance, please do not hesitate to contact us.</p>
        <a href="./mt4.php" class="btn btn-primary">Return to Home</a>
    </div>
</body>
</html>

