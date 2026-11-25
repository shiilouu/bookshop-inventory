
document.addEventListener("DOMContentLoaded", function () {

    // Sidebar toggle
    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");

    if (menuToggle && sidebar) {
        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
        });
    }

    // Password visibility
    document.querySelectorAll(".password-toggle").forEach(function (icon) {
        icon.addEventListener("click", function () {
            const targetId = icon.getAttribute("data-target");
            const passwordInput = document.getElementById(targetId);

            if (passwordInput) {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        });
    });

    // Registration password validation
    const registerForm = document.getElementById("registerForm");

    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {
            const password = document.getElementById("registerPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                event.preventDefault();
                alert("Passwords do not match!");
            }
        });
    }

    // Demo form submission
    document.querySelectorAll(".demo-form").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            alert("This is a front-end preview. Database integration will be handled by the back-end team.");
            form.reset();

            const modal = form.closest(".modal");

            if (modal) {
                modal.classList.remove("show");
            }
        });
    });

});

// Open modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);

    if (modal) {
        modal.classList.add("show");
    }
}

// Close modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);

    if (modal) {
        modal.classList.remove("show");
    }
}

// Search table
function searchTable(input, tableId) {
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);

    if (!table) return;

    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(function (row) {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
}

// Demo message
function showMessage(message) {
    alert(message);
}

// Close modal when clicking outside
window.addEventListener("click", function (event) {
    if (event.target.classList.contains("modal")) {
        event.target.classList.remove("show");
    }
});
