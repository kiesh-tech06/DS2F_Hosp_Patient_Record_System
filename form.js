// Appointment form validation + status messages
document.addEventListener("DOMContentLoaded", function () {
    const appointmentForm = document.getElementById("appointmentForm");
    const formMessage = document.getElementById("formMessage");

    // Stop if these elements are not on the page (safety check)
    if (!appointmentForm || !formMessage) {
        return;
    }

    // 1. Show a message after the page reloads, based on the ?status= flag
    //    that submit_appointment.php adds to the URL.
    const params = new URLSearchParams(window.location.search);
    const status = params.get("status");

    if (status === "success") {
        formMessage.textContent = "Appointment request submitted successfully!";
        formMessage.style.color = "green";
    } else if (status === "invalid") {
        formMessage.textContent = "Please enter a valid email address.";
        formMessage.style.color = "red";
    } else if (status === "error") {
        formMessage.textContent = "Something went wrong. Please fill in all required fields and try again.";
        formMessage.style.color = "red";
    }

    // 2. Validate the form in the browser before it submits.
    appointmentForm.addEventListener("submit", function (event) {
        const fullName = document.getElementById("full_name").value.trim();
        const email    = document.getElementById("email").value.trim();
        const phone    = document.getElementById("phone").value.trim();

        // If any required field is empty, stop the submit and show a message
        if (fullName === "" || email === "" || phone === "") {
            event.preventDefault();   // do not send the form
            formMessage.textContent = "Please fill in all required fields.";
            formMessage.style.color = "red";
            return;
        }

        // Simple email format check
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            event.preventDefault();
            formMessage.textContent = "Please enter a valid email address.";
            formMessage.style.color = "red";
            return;
        }

        // If everything is fine, the form submits normally to
        // submit_appointment.php, which saves it to the database.
    });
});
