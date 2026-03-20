(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var likeForm = document.getElementById("songLikeForm");
        var likeButton = document.getElementById("songLikeButton");
        var likeLabel = document.getElementById("songLikeLabel");
        var likeCount = document.getElementById("songLikeCount");

        if (!likeForm || !likeButton || !likeLabel || !likeCount) return;

        likeForm.addEventListener("submit", async function (e) {
            e.preventDefault();
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
                    likeLabel.textContent = data.userLiked ? "♥ Quitar like" : "♥ Dar like";
                    likeCount.textContent = String(data.likesTotal || 0);
                }
            } catch (err) {
                likeForm.submit();
            } finally {
                likeButton.disabled = false;
            }
        });
    });
})();
