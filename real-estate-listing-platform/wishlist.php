<?php
include("./common.php");
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$conn = connectToDatabase();

$query = "SELECT wishlist FROM USERS WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$wishlist = "";
if ($row = $result->fetch_assoc()) {
    $wishlist = $row["wishlist"];
}
$stmt->close();

$wishlistArray = explode(",", $wishlist);
$dbresults = [];
foreach ($wishlistArray as $wish) {
    if (!empty($wish)) {
        $info = getPropertyInfo($wish, $conn);
        if ($info !== null) {
            $dbresults[] = $info;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap.css" rel="stylesheet">
    <link href="./mt4.css" rel="stylesheet">
    <title>Buy'n'Sell-Property-Ecosystem</title>
</head>
<body>
    <div class="topnav">
        <a href="./mt4.php">Home</a>
        <a href="./wishlist.php">Wishlist</a>
        <a href="./login.php?logout=true">Log Out</a>
    </div>
    <br>
    <div class="container-fluid">
        <div class="row">
            <?php if (empty($dbresults)): ?>
                <div class="col-12 text-center"> 
                    <p>Your wishlist is empty. Start adding new items!</p>
                </div>
            <?php else: ?>
                <?php foreach ($dbresults as $row): ?>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <img class="card-img-top" src="<?= htmlspecialchars($row["img_url"]) ?>" alt="Card image" style="width: 100%; height: 250px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h4 class="card-title"><?= htmlspecialchars($row["title"]) ?></h4>
                                <p class="card-text"><?= htmlspecialchars($row["description_"]) ?></p>
                                <div class="mt-auto">
                                    <a href="<?= htmlspecialchars($row["url_"]) ?>" class="btn btn-primary">See details</a>
                                    <a href="./wishlist-item.php?action=remove&id=<?= $row["id"] ?>" class="btn btn-danger">Remove from Wishlist</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
