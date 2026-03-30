document.addEventListener("DOMContentLoaded", function () {
    const fields = [
        "firstName",
        "lastName",
        "email",
        "phone",
        "company"
    
    ];

    const progressBar = document.getElementById("profileProgressBar");
    const progressText = document.getElementById("profileProgressText");
    const editBtn = document.getElementById("editProfileBtn");
    const saveBtn = document.getElementById("saveProfileBtn");

    function updateProgress() {
        let filled = 0;
        fields.forEach((id) => {
            const input = document.getElementById(id);
            if (input && input.value.trim() !== "") {
                filled++;
            }
        });

        const percent = Math.round((filled / fields.length) * 100);
        progressBar.style.width = percent + "%";

        // Emoji status
        let emoji = "🟥";
        if (percent >= 80) emoji = "✅";
        else if (percent >= 50) emoji = "🟡";

        progressText.textContent = `${emoji} ${percent}% Complete`;

        // Optional bar color
        progressBar.classList.remove("bg-danger", "bg-warning", "bg-success");
        if (percent < 50) {
            progressBar.classList.add("bg-danger");
        } else if (percent < 80) {
            progressBar.classList.add("bg-warning");
        } else {
            progressBar.classList.add("bg-success");
        }
    }

    function toggleInputs(disabled) {
        fields.forEach((id) => {
            const input = document.getElementById(id);
            if (input) input.disabled = disabled;
        });
    }

    editBtn.addEventListener("click", () => {
        toggleInputs(false);
        saveBtn.classList.remove("d-none");
        editBtn.disabled = true;
    });

    saveBtn.addEventListener("click", () => {
        alert("✅ Saving data... (You can replace this with your AJAX call)");
    });

    updateProgress();

    fields.forEach((id) => {
        const input = document.getElementById(id);
        input?.addEventListener("input", updateProgress);
    });
});
