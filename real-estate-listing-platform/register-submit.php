<?php
    include("./common.php");
    session_start();

    $errors = [];
    $status = "";

    if(!isset($_POST["fname"]) || $_POST["fname"] == ""){
        $errors[] = "First name cannot be blank.";
        $status = "error";
    }

    if(!isset($_POST["lname"]) || $_POST["lname"] == ""){
        $errors[] = "Last name cannot be blank.";
        $status = "error";
    }

    if(!isset($_POST["username"]) || $_POST["username"] == ""){
        $errors[] = "Username cannot be blank.";
        $status = "error";
    }

    if(!isset($_POST["email"]) || $_POST["email"] == ""){
        $errors[] = "Please provide a valid email address.";
        $status = "error";
    }

    if(!isset($_POST["user_type"]) || $_POST["user_type"] == ""){
        $errors[] = "Please select a user type.";
        $status = "error";
    }

    if(!isset($_POST["password"]) || $_POST["password"] == ""){
        $errors[] = "Password cannot be blank.";
        $status = "error";
    }
    elseif(!isset($_POST["cpassword"]) || $_POST["cpassword"] == ""){
        $errors[] = "Please confirm password.";
        $status = "error";
    }
    elseif($_POST["password"] != $_POST["cpassword"]){
        $errors[] = "Passwords do not match.";
        $status = "error";
    }

    if($status == "error"){
        $_SESSION["status"] = $status;
        $_SESSION["errors"] = $errors;
        header("Location: ./register.php");
    }
    else{
        $fname = $_POST["fname"];
        $lname = $_POST["lname"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $user_type = $_POST["user_type"];
        $password = $_POST["password"];

        $password = encrypt($password, ENCRYPTION_KEY);

        //create connection
        $conn = connectToDatabase();

        //Insert data into database
        $stmt = "INSERT INTO USERS (fname, lname, username, email, user_type, password)
        VALUES ('$fname', '$lname', '$username', '$email', '$user_type', '$password')";
        
        if($conn->query($stmt) === TRUE){
            header("Location: ./login.php?register=TRUE");
        }
        else {
            header("Location: ./login.php?register=FALSE");
        }

        if(isset($_SESSION["status"])){
            unset($_SESSION["status"]);
            unset($_SESSION["errors"]);
        }

        $conn->close();
    }
?>