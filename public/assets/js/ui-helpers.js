// ✅ Highlight cards with primary badge
function highlightPrimaryCards() {
    $(".card").each(function () {
        if ($(this).find(".primary-badge").length > 0) {
            $(this)
                .addClass("border-success shadow-lg")
                .removeClass("border-secondary");
        } else {
            $(this)
                .removeClass("border-success shadow-lg")
                .addClass("border-secondary");
        }
    });
}

// ✅ Show or hide loading spinner
function showLoading(show) {
    const spinner = document.getElementById("loadingSpinner");
    const table = document.getElementById("mealsTable");
    const emptyState = document.getElementById("emptyState");

    if (show) {
        spinner.style.display = "flex";
        table.style.display = "none";
        emptyState.style.display = "none";
    } else {
        spinner.style.display = "none";
    }
}

// ✅ Show toast message
function showToast(message, type) {
    const toast = document.createElement("div");
    toast.className = `alert alert-${
        type === "success" ? "success" : "danger"
    } position-fixed`;
    toast.style.cssText =
        "top: 20px; right: 20px; z-index: 9999; opacity: 0; transition: opacity 0.3s ease;";
    toast.innerHTML = `
        <i class="fas fa-${
            type === "success" ? "check-circle" : "exclamation-circle"
        } me-2"></i>
        ${message}
    `;
    document.body.appendChild(toast);
    setTimeout(() => (toast.style.opacity = "1"), 100);
    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
