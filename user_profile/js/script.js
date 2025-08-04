// ========== FORM VALIDATIONS ==========

// Validate registration form
function validateRegisterForm() {
    let name = document.querySelector('input[name="name"]').value.trim();
    let email = document.querySelector('input[name="email"]').value.trim();
    let password = document.querySelector('input[name="password"]').value;
    let cpassword = document.querySelector('input[name="cpassword"]').value;

    // Check all fields filled
    if (!name || !email || !password || !cpassword) {
        alert("Please fill in all fields.");
        return false;
    }

    // Check email format
    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        alert("Enter a valid email address.");
        return false;
    }

    // Password match
    if (password !== cpassword) {
        alert("Passwords do not match.");
        return false;
    }

    return true;
}

// Validate login form
function validateLoginForm() {
    let email = document.querySelector('input[name="email"]').value.trim();
    let password = document.querySelector('input[name="password"]').value;

    if (!email || !password) {
        alert("Please enter both email and password.");
        return false;
    }

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        alert("Enter a valid email address.");
        return false;
    }

    return true;
}

// ========== POPUP CONFIRMATIONS ==========

// Confirm logout
function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
        window.location = "logout.php";
    }
}

// Confirm task delete
function confirmDelete() {
    return confirm("Are you sure you want to delete this task?");
}

// Confirm toggle status
function confirmToggle() {
    return confirm("Do you want to change this task status?");
}

// Confirm cancel edit
function confirmCancelEdit() {
    return confirm("Discard changes and return to add mode?");
}