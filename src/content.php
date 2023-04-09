<?php ob_start() ?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Myousik Streaming</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <?php
    session_start();
    echo $_SESSION["is_admin"];

    $conn = new mysqli("mysql", "root", "n01415150", "myousik");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT s.*, l.listened_at FROM songs s LEFT JOIN listens l ON l.song_id = s.id";
    $result = $conn->query($sql);
    ?>

    <div class="container">
        <h1 class="center-align">Myousik Streaming</h1>
        <?php if ($result->num_rows > 0): ?>
            <ul class="collection">
                <?php while ($row = $result->fetch_assoc()):
                    $songTitle = htmlspecialchars($row["title"]);
                    $songArtist = htmlspecialchars($row["artist"]);
                    $songAlbum = htmlspecialchars($row["album"]);
                    $listenedAt = $row["listened_at"] ? date("Y-m-d H:i:s", strtotime($row["listened_at"])) : "Not listened yet";
                    ?>
                    <li class="collection-item">
                        <?= "$songTitle - $songArtist - $songAlbum (Listened at: $listenedAt)" ?>
                        <button class="btn-floating btn-small waves-effect waves-light right play-btn"
                            data-src="<?= htmlspecialchars($row["file_path"]) ?>"><i
                                class="material-icons">play_arrow</i></button>
                        <audio preload="none"></audio>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No songs found in the database.</p>
        <?php endif; ?>

        <?php
        $conn->close();
        ?>
    </div>

    <footer class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <p>Authors: Medi Muamba N01320883, Caio Q N01415150 , Aaron Jara N01299554, Chidinma Etumni
                        N00574991</p>
                    <p>Copyright <a
                            href="https://humber.ca/legal-and-risk-management/policies/academic/copyright-policy.html">For
                            more information click here</a></p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const playButtons = document.querySelectorAll(".play-btn");

            playButtons.forEach(button => {
                button.addEventListener("click", function () {
                    const audioElement = this.nextElementSibling;
                    const filePath = this.dataset.src;

                    if (audioElement.paused) {
                        audioElement.src = filePath;
                        audioElement.play();
                        this.innerHTML = '<i class="material-icons">pause</i>';
                    } else {
                        audioElement.pause();
                        this.innerHTML = '<i class="material-icons">play_arrow</i>';
                    }
                });
            });
        });
    </script>

</body>

</html>
<?php ob_end_flush() ?>