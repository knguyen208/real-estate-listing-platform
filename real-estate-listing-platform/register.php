<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register User</title>
    <link rel="stylesheet" href="./register.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="./dashboard.css" rel="stylesheet">
</head>

<body>
    <div class="topnav">
        <a href="about.php">About us</a>
        <a href="login.php">Log in</a>
        
    </div>
    <br>
    <div class ="register_header">
    <h1>Create your account</h1>
    <p id="reg_slogan">Join now and start looking for your dream house.</p>
    </div>
    <div class="reg_element">
         <img id="reg_circle"src ="img/register_element.png">
    </div>
    <div class="reg_rectangle">
        <h2 id="regH2_form">Register</h2>

        <?php
        if (isset($_SESSION["status"]) && $_SESSION["status"] == "error") :
        ?>

            <div>
                <ul>
                    <?php
                    foreach ($_SESSION["errors"] as $error) :
                    ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="register-submit.php" method="post">
            <div>
                <span><label for="fname">First Name: </label></span><br>
                <input type="text" name="fname" id="fname">
            </div>
            <div>
                <span><label for="lname">Last Name: </label></span><br>
                <input type="text" name="lname" id="lname">
            </div>
            <div>
                <span><label for="username">Username: </label></span><br>
                <input type="text" name="username" id="username" maxlength="30">
            </div>
            <div>
                <span><label for="email">Email: </label></span><br>
                <input type="text" name="email" id="email" maxlength="30">
            </div>
            <div>
                <span><label for="utype">User Type: </label></span><br>
                <select name="user_type" id="utype">
                    <option value="">(Select User Type)</option>
                    <option value="seller">Seller</option>
                </select>
            </div>
            <div>
            <br><span><label for="password">Password: </label></span><br>
                <input type="password" name="password" id="password" maxlength="30">
            </div>
            <div>
                <span><label for="cpassword">Confirm Password: </label></span><br>
                <input type="password" name="cpassword" id="cpassword" maxlength="30">
            </div>
            <input type="submit" value="Register">
        </form>
    </div>
</body>

</html>