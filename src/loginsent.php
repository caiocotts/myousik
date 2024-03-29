<?php ob_start() ?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyouSIK Streaming</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php session_start();
    echo $_SESSION["is_admin"]; ?>
    <section class="header-feedsent">
        <nav>
            <br>
            <a href="index.html">
                <img src="images/Logo1.png">
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

        <section class="sent">
            <div class="login-sent">
                <h1>Successful login!</h1>
                <h3>Thank You!</h3>
            </div>
        </section>

        <section class="foot">
            <footer class="footer">
                <p>Authors: Medi Muamba N01320883, Caio Q N01415150 , Aaron Jara N01299554, Chidinma Etumni N00574991
                </p>
                <p>Copyright <a
                        href="https://humber.ca/legal-and-risk-management/policies/academic/copyright-policy.html">For
                        more information click here</a></p>
            </footer>
        </section>

</html>
<?php ob_end_flush() ?>