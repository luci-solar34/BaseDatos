(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var roleSelect = document.getElementById("rol");
        var artistInput = document.getElementById("nombre_artistico");
        var warning = document.getElementById("artist-warning");

        if (!roleSelect || !artistInput || !warning) return;

        function syncArtistField() {
            var isArtist = roleSelect.value === "2";

            artistInput.disabled = !isArtist;

            if (!isArtist) {
                warning.style.display = artistInput.value.trim() !== "" ? "block" : "none";
            } else {
                warning.style.display = "none";
            }
        }

        roleSelect.addEventListener("change", syncArtistField);
        artistInput.addEventListener("input", function () {
            if (roleSelect.value !== "2" && artistInput.value.trim() !== "") {
                warning.style.display = "block";
            } else {
                warning.style.display = "none";
            }
        });

        syncArtistField();
    });
})();
