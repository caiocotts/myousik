<?php ob_start() ?>
<!DOCTYPE html>
<html>

<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["is_admin"]) || !$_SESSION["is_admin"]) {
        http_response_code(401);
        echo "<h1>401</h1>";
        echo "Unauthorized: You do not have permission to access this page.\n";
        echo "</body>\n</html>";
        exit();
    }
    ?>
    <div class="container">
        <h1>Admin console</h1>
        <button onclick="window.location.href='musicmanagement.php';">Music Management</button>
        <h3>User List</h3>
        <?php
        $mysqli = new mysqli("mysql", "root", "n01415150", "myousik");

        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["uid"])) {
            $uid = $mysqli->real_escape_string($_POST["uid"]);
            $mysqli->query("DELETE FROM users WHERE uid = $uid");
        }

        $result = $mysqli->query("SELECT * FROM users");

        if (!$result) {
            echo "Error fetching users: " . $mysqli->error;
            exit();
        }

        echo "<table class='striped'>";
        echo "<thead><tr><th>User ID</th><th>Username</th><th>Hash</th><th>Salt</th><th>Is Admin</th><th>Action</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["uid"] . "</td>";
            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
            echo "<td>" . substr($row["hash"], 0, 10) . "..." . "</td>";
            echo "<td>" . substr($row["salt"], 0, 10) . "..." . "</td>";
            echo "<td>" . $row["is_admin"] . "</td>";
            echo "<td><button class='btn red lighten-2' onclick='deleteUser(" . $row["uid"] . ")'><i class='material-icons'>delete</i></button></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        $mysqli->close();
        ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        function deleteUser(uid) {
            if (confirm("Are you sure you want to delete this user?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", window.location.href, true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        location.reload();
                    }
                };
                xhr.send("uid=" + encodeURIComponent(uid));
            }
        }
    </script>
</body>

</html>
<?php ob_end_flush() ?>