<?php ob_start() ?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Myousik Streaming</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            position: relative;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            background-image: linear-gradient(rgba(99, 77, 88, 0.479), rgba(95, 70, 78, 0.479)), url(/images/background2.jpg);
            background-position: center;
            background-size: cover;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1;
        }

        .brand-logo {
            width: 15px;
        }

        nav {
            background-color: transparent;
            box-shadow: none;
            padding: 2% 3%;
        }

        nav img {
            padding-left: 0%;
            width: 98px;
        }

        * {
            font-family: monospace;
            margin: 0;
            padding: 0;
        }

        /* .foot {
            position: static;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #fec7c4;
            color: black;
            text-align: center;
        } */

        .container {
            flex: 1;
        }

        #fixed-player {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        #fixed-player-btn {
            margin-bottom: 5px;
        }

        #fixed-player-progress {
            width: 90%;
            height: 3px;
        }
    </style>
</head>

<body>
    <div class="main-content"></div>
    <!-- Navbar -->
    <header>
        <nav>
            <div class="nav-wrapper">
                <a href="index.html" class="brand-logo"><img src="images/Logo2.png"></a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="#" class="modal-trigger" data-target="genre-modal">Select Genres</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Genre Modal -->
    <div id="genre-modal" class="modal">
        <div class="modal-content">
            <h4>Select Genres</h4>
            <p>Choose your preferred music genres:</p>
            <form action="#">
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" />
                        <span>Prog Rock</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" />
                        <span>Electronic</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" />
                        <span>Death Jazz</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="checkbox" class="filled-in" />
                        <span>Post Punk</span>
                    </label>
                </p>
                <!-- Add more genres as needed -->
            </form>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect waves-green btn-flat">save</a>
        </div>
    </div>

    <?php
    session_start();
    $user_id = $_SESSION['id'];

    $conn = new mysqli("mysql", "root", "n01415150", "myousik");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT s.*, l.listened_at
            FROM songs s
            LEFT JOIN listens l ON l.song_id = s.id
            WHERE l.user_id = ? OR l.user_id IS NULL
            ORDER BY l.listened_at DESC, s.id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="container">
        <h1 class="center-align">Recently Played</h1>
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
                            data-songid="<?= htmlspecialchars($row["id"]) ?>"
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

    <!-- <section class="foot">
        <footer class="footer">
            <p>Author: Medi Muamba, Caio Q, Aaron Jara, Chidinma Etumni</p>
            <p>Copyright <a
                    href="https://humber.ca/legal-and-risk-management/policies/academic/copyright-policy.html">For
                    more information click here</a></p>
        </footer>
    </section> -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get all play buttons
            const playButtons = document.querySelectorAll(".play-btn");

            // Get fixed player elements
            const fixedPlayer = document.getElementById("fixed-player");
            const fixedPlayerBtn = document.getElementById("fixed-player-btn");
            const fixedPlayerProgress = document.getElementById("fixed-player-progress");

            let currentAudio = null;

            // Add a click event listener to each play button
            playButtons.forEach(button => {
                button.addEventListener("click", async function () {
                    // Get the associated audio element and file path
                    const audioElement = this.nextElementSibling;
                    const filePath = this.dataset.src;


                    // Toggle the playback
                    if (audioElement.paused) {
                        // window.fetch('/update_listened_at.php?song_id=' + "13")
                        const xhr = new XMLHttpRequest()
                        console.log(this.dataset.songid)
                        console.log(this.dataset.src)
                        xhr.open("GET", '/update_listened_at.php?song_id=' + this.dataset.songid)
                        xhr.send(null)
                        // Pause any currently playing audio
                        if (currentAudio && currentAudio !== audioElement) {
                            currentAudio.pause();
                            currentAudio.parentElement.querySelector(".play-btn").innerHTML = '<i class="material-icons">play_arrow</i>';
                        }

                        // Set the source, start playing, and update the fixed player
                        currentAudio = audioElement;
                        audioElement.src = filePath;
                        try {
                            console.log(filePath);
                            await audioElement.play();
                            this.innerHTML = '<i class="material-icons">pause</i>';
                            fixedPlayerBtn.innerHTML = '<i class="material-icons">pause</i>';
                        } catch (error) {
                            console.error("Error playing audio:", error);
                        }
                    } else {
                        // Pause the playback
                        audioElement.pause();
                        this.innerHTML = '<i class="material-icons">play_arrow</i>';
                        fixedPlayerBtn.innerHTML = '<i class="material-icons">play_arrow</i>';
                    }
                });
            });

            // Add a click event listener to the fixed player button
            fixedPlayerBtn.addEventListener("click", function () {
                if (currentAudio) {
                    // Toggle the playback
                    if (currentAudio.paused) {
                        currentAudio.play();
                        this.innerHTML = '<i class="material-icons">pause</i>';
                    } else {
                        currentAudio.pause();
                        this.innerHTML = '<i class="material-icons">play_arrow</i>';
                    }
                }
            });

            // Add an input event listener to the fixed player progress bar
            fixedPlayerProgress.addEventListener("input", function () {
                if (currentAudio) {
                    // Calculate and set the new playback position
                    const newPosition = currentAudio.duration * (this.value / 100);
                    currentAudio.currentTime = newPosition;
                }
            });

            // Update the progress bar while the audio is playing
            setInterval(() => {
                if (currentAudio && !currentAudio.paused) {
                    const progress = (currentAudio.currentTime / currentAudio.duration) * 100;
                    fixedPlayerProgress.value = progress;
                }
            }, 100);

            const genreModal = document.querySelectorAll(".modal");
            M.Modal.init(genreModal);
        });

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds < 10 ? "0" : ""}${remainingSeconds}`;
        }

        fixedPlayerProgress.addEventListener("input", function () {
            if (currentAudio) {
                // Calculate and set the new playback position
                const newPosition = currentAudio.duration * (this.value / 100);
                currentAudio.currentTime = newPosition;

                // Update the timestamp
                const fixedPlayerTimestamp = document.getElementById("fixed-player-timestamp");
                fixedPlayerTimestamp.textContent = `${formatTime(newPosition)} / ${formatTime(currentAudio.duration)}`;
            }
        });

        setInterval(() => {
            if (currentAudio && !currentAudio.paused) {
                const progress = (currentAudio.currentTime / currentAudio.duration) * 100;
                fixedPlayerProgress.value = progress;

                const fixedPlayerTimestamp = document.getElementById("fixed-player-timestamp");
                fixedPlayerTimestamp.textContent = `${formatTime(currentAudio.currentTime)} / ${formatTime(currentAudio.duration)}`;
            }
        }, 100);

    </script>

    <div id="fixed-player" class="fixed-action-btn">
        <button id="fixed-player-btn" class="btn-floating btn-large waves-effect waves-light"><i
                class="material-icons">play_arrow</i></button>
        <input id="fixed-player-progress" type="range" min="0" max="100" value="0">
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>

</html>
<?php ob_end_flush() ?>