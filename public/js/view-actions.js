(function () {
    "use strict";

    function byId(id) {
        return document.getElementById(id);
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll("[data-toggle-target]").forEach(function (trigger) {
            trigger.addEventListener("click", function () {
                var target = byId(trigger.getAttribute("data-toggle-target"));
                if (target) {
                    target.style.display = "block";
                }
            });
        });

        document.querySelectorAll("[data-hide-target]").forEach(function (trigger) {
            trigger.addEventListener("click", function () {
                var target = byId(trigger.getAttribute("data-hide-target"));
                if (target) {
                    target.style.display = "none";
                }
            });
        });

        document.querySelectorAll("[data-auto-submit-target]").forEach(function (field) {
            field.addEventListener("change", function () {
                var form = byId(field.getAttribute("data-auto-submit-target"));
                if (!form) {
                    return;
                }

                var previewId = field.getAttribute("data-preview-img");
                if (previewId && field.files && field.files[0]) {
                    var img = byId(previewId);

                    if (img && window.URL && typeof window.URL.createObjectURL === "function") {
                        try {
                            img.src = window.URL.createObjectURL(field.files[0]);
                        } catch (error) {
                            // Ignore preview errors and continue with upload.
                        }
                    }
                }

                form.submit();
            });
        });

        document.querySelectorAll("[data-auth-required-message]").forEach(function (element) {
            var eventName = element.tagName === "FORM" ? "submit" : "click";
            element.addEventListener(eventName, function (event) {
                event.preventDefault();
                alert(element.getAttribute("data-auth-required-message"));
            });
        });

        // Bloquear SQL injection keywords en todos los formularios (capa frontend)
        var sqlPattern = /\b(SELECT|UNION|INSERT|DELETE|UPDATE|DROP|ALTER|CREATE|TRUNCATE|EXEC|EXECUTE|DECLARE|CAST|CONVERT|SHUTDOWN)\b|--|#|\/\*/i;
        document.querySelectorAll("form").forEach(function (form) {
            form.addEventListener("submit", function (e) {
                var inputs = form.querySelectorAll('input[type="text"], input[type="email"], textarea');
                inputs.forEach(function (input) {
                    if (sqlPattern.test(input.value)) {
                        e.preventDefault();
                        alert("El campo \"" + (input.placeholder || input.name || "texto") + "\" contiene caracteres no permitidos.");
                        input.focus();
                    }
                });
            });
        });
    });
})();