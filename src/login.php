<?php ob_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyouSIK Streaming</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php

    $username = $password = $usernameErr = $passwordErr = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (empty($_POST["username"])) {
            $usernameErr = "name field cannot be empty";
        } else {
            $username = $_POST["username"];
        }
        if (empty($_POST["password"])) {
            $passwordErr = "password field cannot be empty";
        } else {
            $password = $_POST["password"];
        }
        if ($usernameErr === "" && $passwordErr === "") {
            header("Location: loginsent.html");
        }
    }
    ?>

    <section class="header-log">
        <nav>
            <br>
            <a href="index.html">
                <img src="images/Logo2.png">
                <div class="nav-links">
                    <ul>
                        <li><a href="index.html">HOME</a></li>
                        <li><a href="about.html">ABOUT</a></li>
                        <li><a href="information.html">INFORMATION</a></li>
                        <li><a href="feedback.html">FEEDBACK</a></li>
                        <li><a href="login.php">LOGIN</a></li>
                    </ul>
                </div>
        </nav>

        <section class="login-form">
            <div class="form-box">
                <form method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                    <br><br>
                    <h1>Username</h1>
                    <div>
                        <input type="text" name="username" id="username" value="<?php echo $username ?>">
                        <br>
                        <span class="error">
                            <?php echo $usernameErr ?>
                        </span>
                    </div>
                    <h1>Password</h1>
                    <div>
                        <input type="password" name="password" id="password" value="<?php echo $password ?>">
                        <br>
                        <span class="error">
                            <?php echo $passwordErr ?>
                        </span>
                    </div>
                    <a href="information.html">sign up</a>
                    <br><br><br>
                    <input type="submit" id="login-btn" value="Login" />

            </div>
            </form>
        </section>

</body>
<section class="foot">
    <footer class="footer">
        <p>Author: Medi Muamba, Caio Q, Aaron Jara, Chidinma Etumni</p>
        <p>Copyright <a href="https://humber.ca/legal-and-risk-management/policies/academic/copyright-policy.html">For
                more information click here</a></p>
    </footer>
</section>

</html>
<?php
ob_end_flush();
?>