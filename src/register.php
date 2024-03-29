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

    function genSalt()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $length = 60;

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    $username = $usernameErr
        = $passwordErr = $password2Err = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (empty($_POST["username"])) {
            $usernameErr = "name field cannot be empty";
        } else {
            $username = $_POST["username"];
        }
        if (empty($_POST["password"])) {
            $passwordErr = "password field cannot be empty";
        }
        if ($_POST["password"] != $_POST["password2"]) {
            $password2Err = "passwords do not match";
        }

        if ($usernameErr === "" && $passwordErr === "" && $password2Err === "") {
            $dbc = mysqli_connect("mysql", "root", "n01415150", "myousik") or die("error: connection failed");

            $salt = genSalt();
            $hash = hash("sha256", $salt . $_POST["password"], false);

            $sql = "insert into users values (null, '$username', '$salt', '$hash', false);";
            $result = mysqli_query($dbc, "select username from users where username = '$username'");
            if ($result->num_rows > 0) {
                $usernameErr = "user already exists";
            } else {
                mysqli_query($dbc, $sql) or die("error: could not query database");
                mysqli_close($dbc);
                header("Location: login.php");
            }
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
                        <input type="text" name="username" value="<?php echo $username ?>">
                        <br>
                        <span class="error">
                            <?php echo $usernameErr ?>
                        </span>
                    </div>
                    <h1>Password</h1>
                    <div>
                        <input type="password" name="password">
                        <br>
                        <span class="error">
                            <?php echo $passwordErr ?>
                        </span>
                    </div>
                    <h1>Re-enter password</h1>
                    <div>
                        <input type="password" name="password2">
                        <br>
                        <span class="error">
                            <?php echo $password2Err ?>
                        </span>
                    </div>
                    <a href="login.php">login</a>
                    <br><br><br>
                    <input type="submit" id="login-btn" value="Register" />
                    </br></br>

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