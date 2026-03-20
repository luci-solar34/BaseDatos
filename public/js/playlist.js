(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var nowPlaying = document.getElementById("nowPlaying");
        var cover = document.getElementById("nowPlayingCover");
        var title = document.getElementById("nowPlayingTitle");
        var players = document.querySelectorAll(".js-song-player");

        if (!nowPlaying || !cover || !title || players.length === 0) return;

        players.forEach(function (player) {
            player.addEventListener("play", function () {
                var songTitle = player.dataset.title || "Canción";
                var songCover = player.dataset.cover || "Photos/banner_default.png";

                nowPlaying.style.display = "block";
                title.textContent = songTitle;
                cover.src = "/LASK/" + songCover;
            });
        });
    });
})();
