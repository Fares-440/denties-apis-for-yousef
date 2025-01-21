document.addEventListener("DOMContentLoaded", function () {
    var status = document.getElementById("status");
    var statusText = status.textContent.trim().toLowerCase();

    if (statusText === "مؤكد") {
        status.classList.add("success");
    } else if (statusText === "قيد الانتظار") {
        status.classList.add("warning");
    } else if (statusText === "ملغي") {
        status.classList.add("danger");
    }
});
