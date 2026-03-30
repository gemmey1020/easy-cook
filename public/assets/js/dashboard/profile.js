document.addEventListener("DOMContentLoaded", function () {
  const fields = ["firstName", "lastName", "email", "phone", "company"];
  const progressBar = document.getElementById("profileProgressBar");
  const progressText = document.getElementById("profileProgressText");
  const editBtn = document.getElementById("editProfileBtn");
  const saveBtn = document.getElementById("saveProfileBtn");
  const profileProgressText2 = document.getElementById("profileProgressText2");

  

  let editActive = false;

  function updateProgress() {
    let filled = 0;
    fields.forEach((id) => {
      const input = document.getElementById(id);
      if (input && input.value.trim() !== "") filled++;
    });
    const percent = Math.round((filled / fields.length) * 100);
    progressBar.style.width = percent + "%";
    profileProgressText2.value = percent; // Update hidden input for form submission
    let emoji = "🟥";
    if (percent >= 80) emoji = "✅";
    else if (percent >= 50) emoji = "🟡";
    progressText.textContent = `${emoji} ${percent}% Complete`;
    progressBar.classList.remove("bg-danger", "bg-warning", "bg-success");
    if (percent < 50) progressBar.classList.add("bg-danger");
    else if (percent < 80) progressBar.classList.add("bg-warning");
    else progressBar.classList.add("bg-success");
  }

  function toggleInputs(disabled) {
    fields.forEach((id) => {
      const input = document.getElementById(id);
      if (input) input.disabled = disabled;
    });
  }

  // Toggle edit mode
  editBtn.addEventListener("click", () => {
    editActive = !editActive;
    toggleInputs(!editActive);        // إذا editActive=true => inputs enabled
    editBtn.classList.toggle("active", editActive);
    saveBtn.classList.toggle("d-none", !editActive);
  });

//   saveBtn.addEventListener("click", () => {
//     alert("✅ Saving data... (You can replace this with your AJAX call)");
//   });

  updateProgress();
  fields.forEach((id) => {
    const input = document.getElementById(id);
    input?.addEventListener("input", updateProgress);
  });
});
