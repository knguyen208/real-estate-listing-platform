<?php
include("./common.php");
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$id = $_GET["id"] ?? null;
$action = $_GET["action"] ?? null;

if (!$id || !$action) {
    header("Location: errorpage.php");
    exit;
}

$conn = connectToDatabase();

// Fetch the current wishlist
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
$feedback = "";
$newWishlist = $wishlist; 

if ($action == 'remove') {
    if (in_array($id, $wishlistArray)) {
        $wishlistArray = array_diff($wishlistArray, [$id]);
        $newWishlist = implode(",", $wishlistArray);
        $feedback = "Item removed successfully.";
        if (empty($newWishlist)) {
            $feedback = "Your wishlist is now empty. Start adding new items!";
        }
    } else {
        $feedback = "Item not found in wishlist.";
    }
} elseif ($action == 'add') {
    if (!in_array($id, $wishlistArray)) {
        $wishlistArray[] = $id;
        $newWishlist = implode(",", $wishlistArray);
        $feedback = "Item added successfully.";
    } else {
        $feedback = "Item already exists in wishlist.";
    }
}

if ($newWishlist !== $wishlist) {
    $updateQuery = "UPDATE USERS SET wishlist = ? WHERE username = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ss", $newWishlist, $user);
    if ($updateStmt->execute()) {
        header("Location: wishlist.php?feedback=" . urlencode($feedback));
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $updateStmt->close();
} else {
    header("Location: wishlist.php?feedback=" . urlencode($feedback));
}

$conn->close();
?>
