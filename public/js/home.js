(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var input = document.getElementById("search-input");
        var suggestionsDiv = document.getElementById("suggestions");

        if (!input || !suggestionsDiv) return;

        input.addEventListener("input", function () {
            var query = input.value;

            if (query.length < 2) {
                suggestionsDiv.style.display = "none";
                return;
            }

            fetch("/LASK/public/index.php/search/autocomplete?q=" + encodeURIComponent(query))
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    suggestionsDiv.innerHTML = "";
                    if (!Array.isArray(data) || data.length === 0) {
                        suggestionsDiv.style.display = "none";
                        return;
                    }

                    data.forEach(function (item) {
                        var div = document.createElement("div");
                        div.textContent = item.resultado + " (" + item.tipo + ")";
                        div.style.padding = "5px";
                        div.style.cursor = "pointer";
                        div.addEventListener("click", function () {
                            var url = "";
                            if (item.tipo === "cancion") url = "/LASK/public/index.php/song?id=" + item.id;
                            if (item.tipo === "artista") url = "/LASK/public/index.php/artist?id=" + item.id;
                            if (item.tipo === "album") url = "/LASK/public/index.php/album?id=" + item.id;
                            if (item.tipo === "usuario") url = "/LASK/public/index.php/profile?id=" + item.id;
                            if (item.tipo === "tag") url = "/LASK/public/index.php/tag?id=" + item.id;

                            if (url) {
                                window.location.href = url;
                            }
                            suggestionsDiv.style.display = "none";
                        });
                        suggestionsDiv.appendChild(div);
                    });

                    suggestionsDiv.style.display = "block";
                });
        });
    });
})();
