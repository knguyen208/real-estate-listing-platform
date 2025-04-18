<?php
include("./common.php");
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'buyer') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['location'])) {
    $location = $_GET['location'];
    $properties = searchPropertiesByLocation($location);
} else {
    $properties = getAllProperties();
}

function getAllProperties() {
    $conn = connectToDatabase(); 
    $properties = array(); 
    $query = "SELECT id, title, description_, img_url, url_ FROM properties WHERE available = 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row;
        }
    }
    $conn->close(); 
    return $properties; 
}

function searchPropertiesByLocation($location) {
    $conn = connectToDatabase(); 
    $properties = array(); 
    $query = "SELECT id, title, description_, img_url, url_ FROM properties WHERE (city LIKE '%$location%' OR zip_code LIKE '%$location%') AND available = 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row;
        }
    }
    $conn->close(); 
    return $properties; 
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
    <title>Buy Properties</title>
</head>
<body>
    <div class="topnav">
        <a href="./mt4.php">Home</a>
        <a href="./wishlist.php">Wishlist</a>
        <a href="./buy.php" class="active">Buy</a>
        <a href="./login.php?logout=true">Log Out</a>
    </div>
    <br>

    <div class="container">
        <h2>Available Properties</h2>

        <form method="GET" action="buy.php">
            <input type="text" name="location" placeholder="Enter location">
            <button type="submit">Search</button>
        </form>

        <div class="row">
    <?php foreach ($properties as $property): ?>
    <div class="col-sm-6 col-md-4 col-lg-3 card-column">
        <div class="card">
            <img class="card-img-top" src="<?= htmlspecialchars($property['img_url']) ?>" alt="Property image" style="width: 100%; height: 250px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($property['title']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($property['description_']) ?></p>
                <a href="<?= htmlspecialchars($property['url_']) ?>" class="btn btn-primary">View Details</a>
                <a href="buy_property.php?id=<?= $property['id'] ?>" class="btn btn-success">Buy Now</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
