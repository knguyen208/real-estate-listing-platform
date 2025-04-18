<?php
    session_start();
    if(isset($_GET["logout"]) && $_GET["logout"] == true){
        session_destroy();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="./dashboard.css" rel="stylesheet">
</head>

<body>
    <div class="topnav">
        <a href="./about.php">About us</a>
        <a href="register.php">Register</a>
    </div>

    <br>
<div class="welcome_header">
    <h1>Welcome to Team D.I.V.'s <br>Buy'n'Sell-Property-Ecosystem</h1>
    <p id="slogan"><i>Where we bring you one step closer to finding the house of your dreams.</i></p>
</div>
<div class="login_img">
    <img id="img4login"src ="img/login_home.jpeg">
</div>

    <div class="rectangle">
        <div class="loginInfo">
            <h2 id="form_welcome">Welcome Back!</h2>

            <?php if (isset($_GET["register"]) && $_GET["register"] === "TRUE") : ?>

                <div>
                    <h3>REGISTRATION SUCCESSFUL</h3>
                </div>

            <?php endif; ?>

            <?php if (isset($_GET["register"]) && $_GET["register"] === "FALSE") : ?>

                <div>
                    <h3>REGISTRATION ERROR</h3>
                </div>

            <?php endif; ?>

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
           
            <form action="./login-submit.php" method="post">
                <div>
                    <label for="username">Username: </label><br>
                    <input type="text" name="username" id="username" maxlength="30" placeholder="Enter your username">
                </div>
                <div>
                    <label for="password">Password:&nbsp </label><br>
                    <input type="password" name="password" id="password" maxlength="30" placeholder="Enter your password">
                </div>
                <input class="submit_button" type="submit" value="Login">
                <br>
                <br>
            </form>
           
        </div>
        <p id="register_link">Don't have an account with us? <a href="register.php">Sign up Here!</a></p>
    </div>
</body>

</html>
