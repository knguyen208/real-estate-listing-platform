<?php
session_start();
include 'common.php';
$conn = connectToDatabase();

$userLoggedIn = isset($_SESSION['user']);
$userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;
$properties = fetchProperties($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap.css" rel="stylesheet">
    <link href="./dashboard.css" rel="stylesheet">
    <title>Buy'n'Sell-Property-Ecosystem</title>
    <script src="./dashboard.js" defer></script>
</head>
<body>
<div class="topnav">
    <a href="./dashboard.php">Home</a>
    <?php if ($userLoggedIn): ?>
        <?php if ($userType === 'buyer'): ?>
            <a href="./buy.php">Buy</a>
        <?php elseif ($userType === 'seller'): ?>
            <a href="./seller.php">Sell</a>
        <?php endif; ?>
        <a href="./login.php?logout=true">Log Out</a>
    <?php else: ?>
        <a href="./login.php">Log In</a>
        <a href="./register.php">Register</a>
    <?php endif; ?>
</div>   
<br>
<div class="container"> 
    <form>
        <label for="searchInput">Search: </label>
        <input type="text" name="searchInput" id="searchInput">
    </form>
    <div class="row" id="cardContainer">
        <?php foreach ($properties as $property): ?>
        <div class="col-sm-6 card-column">
            <div class="card" style="width: 400px;">
                <img class="card-img-top" src="<?= htmlspecialchars($property['img_url']) ?>" alt="Card image" style="width: 100%; height: 250px; object-fit: cover;">
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($property['title']) ?></h4>
                    <p class="card-text"><?= htmlspecialchars($property['description_']) ?></p>
                    <p class="price"><strong>Price: $<?= number_format(htmlspecialchars($property['price'])) ?></strong></p>
                    <a href="<?= htmlspecialchars($property['url_']) ?>" class="btn btn-primary">See details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>