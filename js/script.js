document.addEventListener('DOMContentLoaded', () => {
    const toggleThemeBtn = document.getElementById("toggle-theme");
    const todoContainer = document.getElementById("todoContainer");

    // Apply saved theme on load
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "light") {
        document.body.classList.add("light-mode");
    }

    // Animation on load
    window.addEventListener('load', () => {
        if (todoContainer) {
            todoContainer.classList.add('show');
        }
    });

    // Theme toggle and save preference
    if (toggleThemeBtn) {
        toggleThemeBtn.addEventListener("click", () => {
            document.body.classList.toggle("light-mode");
            if (document.body.classList.contains("light-mode")) {
                localStorage.setItem("theme", "light");
            } else {
                localStorage.setItem("theme", "dark");
            }
        });
    }
});

// Show edit form with data
function showEditForm(task) {
    document.getElementById('editSection').style.display = 'block';
    document.getElementById('edit_id').value = task.id;
    document.getElementById('edit_title').value = task.title;
    document.getElementById('edit_description').value = task.description;
    window.scrollTo(0, document.body.scrollHeight);
}