CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    salt VARCHAR(255),
    hash VARCHAR(255),
    is_admin BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE songs (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    artist VARCHAR(255),
    album VARCHAR(255),
    file_path VARCHAR(255)
);

CREATE TABLE preferences (
    user_id INT,
    genre VARCHAR(255),
    PRIMARY KEY (user_id),
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE listens (
    user_id INT,
    song_id INT,
    listened_at DATETIME,
    PRIMARY KEY (user_id, song_id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (song_id) REFERENCES songs (id)
);


-- select s.*, l.listenedAt from songs s left join listens l on (l.song_id = s.id);