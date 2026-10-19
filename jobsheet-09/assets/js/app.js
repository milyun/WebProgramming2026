// ===== Hamburger menu (JS-driven, replaces the checkbox hack) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== Delete confirmation =====
// The Delete button now lives inside <form class="form-delete" method="post">
// which actually sends a DELETE request to the server (books/delete.php,
// members/delete.php). Confirmation happens on the "submit" event so it can
// be cancelled (preventDefault) before the request is sent.
function initDeleteConfirm() {
    document.addEventListener("submit", function (e) {
        const form = e.target;
        if (!form.classList.contains("form-delete")) return;

        const row = form.closest("tr");
        const name = row ? row.querySelector("td")?.textContent : "this item";
        const confirmed = confirm("Are you sure you want to delete \"" + name + "\"?");
        if (!confirmed) {
            e.preventDefault();
        }
    });
}

// ===== Real-time table filter/search =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
}

// ===== Form validation (client-side) =====
function showError(input, message) {
    clearError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = message;
    input.insertAdjacentElement("afterend", span);
}

function clearError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initFormValidation() {
    const form = document.getElementById("form-add");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;

        const titleOrName = form.querySelector("[name='title'], [name='name']");
        if (titleOrName && titleOrName.value.trim() === "") {
            showError(titleOrName, "This field is required.");
            valid = false;
        } else if (titleOrName) {
            clearError(titleOrName);
        }

        const author = form.querySelector("[name='author']");
        if (author && author.value.trim() === "") {
            showError(author, "Author is required.");
            valid = false;
        } else if (author) {
            clearError(author);
        }

        const year = form.querySelector("[name='year']");
        if (year) {
            const value = parseInt(year.value, 10);
            if (isNaN(value) || value < 1900 || value > 2026) {
                showError(year, "Year must be between 1900-2026.");
                valid = false;
            } else {
                clearError(year);
            }
        }

        const stock = form.querySelector("[name='stock']");
        if (stock) {
            const value = parseInt(stock.value, 10);
            if (isNaN(value) || value < 0) {
                showError(stock, "Stock cannot be negative.");
                valid = false;
            } else {
                clearError(stock);
            }
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initDeleteConfirm();
    initTableFilter();
    initFormValidation();
});
