// appointment form validation
document.addEventListener("DOMContentLoaded", function () {
    const appointmentForm = document.getElementById("appointmentForm");
    const formMessage = document.getElementById("formMessage");

    appointmentForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const patientName = document.getElementById("patientName").value.trim();
        const patientEmail = document.getElementById("patientEmail").value.trim();
        const patientPhone = document.getElementById("patientPhone").value.trim();
        const appointmentDate = document.getElementById("appointmentDate").value;
        const appointmentTime = document.getElementById("appointmentTime").value;
        const department = document.getElementById("department").value;

        if (
            patientName === "" ||
            patientEmail === "" ||
            patientPhone === "" ||
            appointmentDate === "" ||
            appointmentTime === "" ||
            department === ""
        ) {
            formMessage.textContent = "Please fill in all required fields.";
            formMessage.style.color = "red";
            return;
        }

        formMessage.textContent = "Appointment form submitted successfully!";
        formMessage.style.color = "green";

        appointmentForm.reset();
    });
});