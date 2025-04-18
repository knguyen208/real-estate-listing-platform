<?php
    define('ENCRYPTION_KEY', '__^%&Q@$&*!@#$%^&*^__');
    
    function getPropertyInfo($id, $conn) {
        $id = intval($id);
        $query = "SELECT id, title, description_, img_url, url_ FROM PROPERTIES WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $propertyInfo = $result->fetch_assoc();
        $stmt->close();
    
        return $propertyInfo;
    }

    function fetchProperties($conn) {
        $properties = [];
        $query = "SELECT p.id, p.title, p.description_, p.img_url, p.url_, p.price, pi.img_url AS additional_img
                  FROM properties p
                  LEFT JOIN property_images pi ON p.id = pi.property_id";
        $result = $conn->query($query);
        
        while ($row = $result->fetch_assoc()) {
            if (!isset($properties[$row['id']])) {
                $properties[$row['id']] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'description_' => $row['description_'],
                    'img_url' => $row['img_url'],
                    'url_' => $row['url_'],
                    'price' => $row['price'],
                    'images' => []
                ];
            }
            if ($row['additional_img']) {
                $properties[$row['id']]['images'][] = $row['additional_img'];
            }
        }
        return array_values($properties); 
    }

    function connectToDatabase(){
        $host = "localhost";
        $user = "knguyen208";
        $pass = "knguyen208";
        $dbname = "knguyen208";

        $conn = new mysqli($host, $user, $pass, $dbname);
        if($conn->connect_error){
            echo "Could not connect to server\n";
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    function authenticate_user($username, $password){

        $conn = connectToDatabase();

        $query = "SELECT password FROM USERS WHERE username = '$username'";
        $result = $conn->query($query);
        $truepword = "";
        while($row = $result->fetch_array()){
            $truepword = $row["password"];
        }

        $truepword = decrypt($truepword, ENCRYPTION_KEY);

        $conn->close();

        return $truepword == $password;
    }

    function encrypt($data, $key){
        $encrypted = base64_encode($data ^ $key);
        return $encrypted;
    }
    
    function decrypt($data, $key){
        $decrypted = base64_decode($data) ^ $key;
        return $decrypted;
    }
?>