<?php
include 'common.php'; // Include the common functions and database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST["address"];
    $property_type = $_POST["property_type"];
    $bedrooms = $_POST["bedrooms"];
    $bathrooms = $_POST["bathrooms"];
    $square_footage = $_POST["square_footage"];
    $description = $_POST["description"];
    $city = $_POST["city"];
    $zip_code = $_POST["zip_code"];
    $price = $_POST["price"];
    $title = $bedrooms . " Bedroom " . $property_type;

    $target_dir = "uploads/";
    $uploadOk = 1;
    $main_image_path = $target_dir . basename($_FILES["image"]["name"]); // Main image handling
    $imageFileType = strtolower(pathinfo($main_image_path, PATHINFO_EXTENSION));

    // Validate and move the main image
    if (!getimagesize($_FILES["image"]["tmp_name"]) || $_FILES["image"]["size"] > 500000 || !in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "File is not a valid image or too large.";
        $uploadOk = 0;
    } else {
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $main_image_path)) {
            echo "Failed to upload main image.";
            $uploadOk = 0;
        }
    }

    $image_paths = [$main_image_path]; 

    if ($uploadOk && !empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $index => $file_name) {
            $file_tmp = $_FILES['images']['tmp_name'][$index];
            $additional_path = $target_dir . basename($file_name);
            if (move_uploaded_file($file_tmp, $additional_path)) {
                $image_paths[] = $additional_path;
            }
        }
    }

    if ($uploadOk) {
        $conn = connectToDatabase();

        $stmt = $conn->prepare("INSERT INTO properties (title, address, property_type, bedrooms, bathrooms, square_footage, description_, img_url, city, zip_code, url_, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $htmlFilename = "property_" . time() . ".html";
        $htmlFilePath = "links/" . $htmlFilename;
        $stmt->bind_param("sssiiisssssi", $title, $address, $property_type, $bedrooms, $bathrooms, $square_footage, $description, $main_image_path, $city, $zip_code, $htmlFilePath, $price);
        $stmt->execute();
        $property_id = $conn->insert_id; 

        foreach ($image_paths as $path) {
            $stmt = $conn->prepare("INSERT INTO property_images (property_id, img_url) VALUES (?, ?)");
            $stmt->bind_param("is", $property_id, $path);
            $stmt->execute();
        }

        $htmlContent = "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link href='../bootstrap.css' rel='stylesheet'>
            <link href='../dashboard.css' rel='stylesheet'>
            <style>
                img {
                    width: 400px; 
                    height: 300px; 
                    border: solid black 1px; 
                    margin-bottom: 10px;
                }
                p {
                    padding: 5px; 
                    text-align: justify; 
                }
                .list-group-item {
                    width: fit-content; 
                    margin-bottom: 5px; 
                }
            </style>
            <title>{$property_type} at {$address}</title>
        </head>
        <body>
            <div class='topnav'>
                <a href='../dashboard.php'>Home</a>
                <a href='../wishlist.php'>Wishlist</a>
                <a href='../login.php?logout=true'>Log Out</a>
            </div>
            <h2>{$bedrooms} Bedroom {$property_type}</h2>
            <h3>Price: $" . number_format($price) . "</h3>
            <h6>{$address}, {$city}, {$zip_code}</h6>";
        
        foreach ($image_paths as $img) {
            $htmlContent .= "<img src='../{$img}' alt='Property image'>";
        }
        
        $htmlContent .= "
            <br>
            <ul class='list-group' style='width: fit-content;'>
                <li class='list-group-item'>Square Footage: {$square_footage} sqft</li>
                <li class='list-group-item'>Bedrooms: {$bedrooms}</li>
                <li class='list-group-item'>Bathrooms: {$bathrooms}</li>
                <li class='list-group-item'>Price: $" . number_format($price) . "</li>
            </ul>
            <p>{$description}</p>
        </body>
        </html>";
          

        file_put_contents($htmlFilePath, $htmlContent);

        $stmt->close();
        $conn->close();

        echo "Property added successfully. <a href='{$htmlFilePath}'>View property</a>";
    } else {
        echo "Failed to upload images or create property listing.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./bootstrap.css" rel="stylesheet">
    <link href="./dashboard.css" rel="stylesheet">
    <title>Seller Page</title>
</head>
<body>
<div class="topnav">
    <a href="./dashboard.php">Home</a>
    <a href="./login.php?logout=true">Log Out</a>
</div>
<div class="container">
    <h2 class="my-4">List Your Property</h2>
    <form method="post" enctype="multipart/form-data" class="mb-3">
        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <input type="text" class="form-control" name="address" id="address">
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="city" class="form-label">City:</label>
                    <input type="text" class="form-control" name="city" id="city">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="zip_code" class="form-label">Zip Code:</label>
                    <input type="text" class="form-control" name="zip_code" id="zip_code">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="property_type" class="form-label">Property Type:</label>
                    <select class="form-control" name="property_type" id="property_type">
                        <option value="">Select a property type</option>
                        <option value="House">House</option>
                        <option value="Apartment">Apartment</option>
                        <option value="Condo">Condo</option>
                        <option value="Townhouse">Townhouse</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="bedrooms" class="form-label">Bedrooms:</label>
                    <input type="number" class="form-control" name="bedrooms" id="bedrooms">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="bathrooms" class="form-label">Bathrooms:</label>
                    <input type="number" class="form-control" name="bathrooms" id="bathrooms">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="square_footage" class="form-label">Square Footage:</label>
                    <input type="number" class="form-control" name="square_footage" id="square_footage">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price:</label>
            <input type="text" class="form-control" name="price" id="price">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Upload Main Image:</label>
            <input type="file" class="form-control" name="image" id="image">
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Upload Additional Images:</label>
            <input type="file" class="form-control" name="images[]" id="images" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

</body>
</html>
