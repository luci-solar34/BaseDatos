(function () {
    "use strict";

    var albumDataElement = document.getElementById("albumPageData");
    var albumQueue = [];
    var albumIndex = 0;

    if (albumDataElement) {
        try {
            albumQueue = JSON.parse(albumDataElement.getAttribute("data-album-queue") || "[]");
        } catch (error) {
            albumQueue = [];
        }
    }

    function byId(id) {
        return document.getElementById(id);
    }

    function renderNowPlayingLike(track) {
        var likeButton = byId("nowPlayingLikeButton");
        if (!likeButton) return;
        likeButton.textContent = (track.userLiked ? "♥ Quitar like" : "♥ Dar like") + " (" + track.likesTotal + ")";
    }

    function renderSongLikeInList(songId, likesTotal) {
        var el = document.querySelector('[data-song-like-count="' + songId + '"]');
        if (el) el.textContent = "♥ " + likesTotal;
    }

    function albumPlay(idx) {
        if (idx === null || idx === undefined || !albumQueue[idx]) return;
        albumIndex = idx;
        var track = albumQueue[idx];

        var audio = byId("albumAudio");
        if (!audio) return;

        audio.src = track.src;
        audio.play();

        byId("nowPlayingTitle").textContent = track.title;
        byId("nowPlayingCover").src = track.cover;
        byId("nowPlaying").style.display = "block";
        byId("lyricText").textContent = track.letra || "Texto no disponible";
        byId("lyricPhonetic").textContent = track.fonetico || "Texto no disponible";
        byId("nowPlayingSongId").value = track.id;
        renderNowPlayingLike(track);
        byId("nowPlayingLikeForm").style.display = "block";
    }

    function albumNext() {
        if (albumIndex < albumQueue.length - 1) albumPlay(albumIndex + 1);
    }

    function albumPrev() {
        if (albumIndex > 0) albumPlay(albumIndex - 1);
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[data-album-play-index]").forEach(function (button) {
            button.addEventListener("click", function () {
                albumPlay(parseInt(button.getAttribute("data-album-play-index"), 10));
            });
        });

        document.querySelectorAll("[data-album-action='next']").forEach(function (button) {
            button.addEventListener("click", albumNext);
        });

        document.querySelectorAll("[data-album-action='prev']").forEach(function (button) {
            button.addEventListener("click", albumPrev);
        });

        var audio = byId("albumAudio");
        if (audio) {
            audio.addEventListener("ended", albumNext);
        }

        var likeForm = byId("nowPlayingLikeForm");
        var likeButton = byId("nowPlayingLikeButton");
        if (!likeForm || !likeButton) return;

        likeForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            if (!albumQueue[albumIndex]) return;

            likeButton.disabled = true;
            try {
                var response = await fetch(likeForm.action, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        Accept: "application/json"
                    },
                    body: new FormData(likeForm)
                });

                if (response.status === 401) {
                    window.location.href = "/LASK/public/index.php/login";
                    return;
                }

                if (!response.ok) {
                    throw new Error("Error al actualizar like");
                }

                var data = await response.json();
                if (data && data.success) {
                    var track = albumQueue[albumIndex];
                    track.userLiked = !!data.userLiked;
                    track.likesTotal = parseInt(data.likesTotal || 0, 10);
                    renderNowPlayingLike(track);
                    renderSongLikeInList(track.id, track.likesTotal);
                }
            } catch (err) {
                likeForm.submit();
            } finally {
                likeButton.disabled = false;
            }
        });
    });
})();
