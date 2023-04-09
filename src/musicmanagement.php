<?php ob_start() ?>
<!DOCTYPE html>
<html>

<head>
    <title>Song List</title>
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
        <button onclick="window.location.href='usermanagement.php';">User Management</button>
        <h3>Song List</h3>
        <?php
        $mysqli = new mysqli("mysql", "root", "n01415150", "myousik");

        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "create") {
            $title = $mysqli->real_escape_string($_POST["title"]);
            $artist = $mysqli->real_escape_string($_POST["artist"]);
            $album = $mysqli->real_escape_string($_POST["album"]);
            $file_path = $mysqli->real_escape_string($_POST["file_path"]);

            $mysqli->query("INSERT INTO songs (title, artist, album, file_path) VALUES ('$title', '$artist', '$album', '$file_path')");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "delete") {
            $id = $mysqli->real_escape_string($_POST["id"]);
            $mysqli->query("DELETE FROM songs WHERE id = $id");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "update") {
            $id = $mysqli->real_escape_string($_POST["id"]);
            $title = $mysqli->real_escape_string($_POST["title"]);
            $artist = $mysqli->real_escape_string($_POST["artist"]);
            $album = $mysqli->real_escape_string($_POST["album"]);
            $file_path = $mysqli->real_escape_string($_POST["file_path"]);

            $mysqli->query("UPDATE songs SET title = '$title', artist = '$artist', album = '$album', file_path = '$file_path' WHERE id = $id");
        }

        $result = $mysqli->query("SELECT * FROM songs");

        if (!$result) {
            echo "Error fetching songs: " . $mysqli->error;
            exit();
        }

        echo "<table class='striped'>";
        echo "<thead><tr><th>ID</th><th>Title</th><th>Artist</th><th>Album</th><th>File Path</th><th>Action</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["artist"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["album"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["file_path"]) . "</td>";
            echo "<td><button class='btn red lighten-2' onclick='deleteSong(" . $row["id"] . ")'><i class='material-icons'>delete</i></button></td>";
            echo "<td><button class='btn blue lighten-2' onclick='openEditModal(" . $row["id"] . ", \"" . htmlspecialchars(addslashes($row["title"])) . "\", \"" . htmlspecialchars(addslashes($row["artist"])) . "\", \"" . htmlspecialchars(addslashes($row["album"])) . "\", \"" . htmlspecialchars(addslashes($row["file_path"])) . "\")'><i class='material-icons'>edit</i></button></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        $mysqli->close();
        ?>
    </div>
    <div class="container">
        <h2>Add New Song</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="action" value="create">
            <div class="input-field">
                <input type="text" name="title" id="title" required>
                <label for="title">Song Title</label>
            </div>
            <div class="input-field">
                <input type="text" name="artist" id="artist" required>
                <label for="artist">Artist</label>
            </div>
            <div class="input-field">
                <input type="text" name="album" id="album" required>
                <label for="album">Album</label>
            </div>
            <div class="input-field">
                <input type="text" name="file_path" id="file_path" required>
                <label for="file_path">File Path</label>
            </div>
            <button type="submit" class="btn waves-effect waves-light">Add Song</button>
        </form>
    </div>

    <div id="editSongModal" class="modal">
        <div class="modal-content">
            <h4>Edit Song</h4>
            <form id="editSongForm" method="post">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                <div class="input-field">
                    <input type="text" name="title" id="edit_title" required>
                    <label for="edit_title">Song Title</label>
                </div>
                <div class="input-field">
                    <input type="text" name="artist" id="edit_artist" required>
                    <label for="edit_artist">Artist</label>
                </div>
                <div class="input-field">
                    <input type="text" name="album" id="edit_album" required>
                    <label for="edit_album">Album</label>
                </div>
                <div class="input-field">
                    <input type="text" name="file_path" id="edit_file_path" required>
                    <label for="edit_file_path">File Path</label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn blue lighten-2 waves-effect waves-light" onclick="submitEditForm()">Save
                Changes</button>
            <button type="button" class="btn grey lighten-1 modal-close waves-effect waves-light">Cancel</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        function deleteSong(id) {
            if (confirm("Are you sure you want to delete this song?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", window.location.href, true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        location.reload();
                    }
                };
                xhr.send("action=delete&id=" + encodeURIComponent(id));
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems);
        });

        function openEditModal(id, title, artist, album, file_path) {
            var modal = document.querySelector('#editSongModal');
            M.Modal.getInstance(modal).open();

            document.querySelector('#edit_id').value = id;
            document.querySelector('#edit_title').value = title;
            document.querySelector('#edit_artist').value = artist;
            document.querySelector('#edit_album').value = album;
            document.querySelector('#edit_file_path').value = file_path;

            M.updateTextFields();
        }

        function serializeForm(form) {
            var formData = new FormData(form);
            var encodedData = [];
            for (var pair of formData.entries()) {
                encodedData.push(encodeURIComponent(pair[0]) + "=" + encodeURIComponent(pair[1]));
            }
            return encodedData.join("&");
        }

        function submitEditForm() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", window.location.href, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload();
                }
            };
            var form = document.querySelector('#editSongForm');
            xhr.send(serializeForm(form));
        }
    </script>
</body>

</html>
<?php ob_end_flush() ?>