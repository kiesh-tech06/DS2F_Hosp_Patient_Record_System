<?php
// Bring in the database connection we made earlier
include 'db_connect.php';

// If a delete was requested, remove that row first
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = intval($_GET['delete']);
    if ($_GET['type'] === 'appointment') {
        $stmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id = ?");
    } else if ($_GET['type'] === 'request') {
        $stmt = $conn->prepare("DELETE FROM appointment_requests WHERE request_id = ?");
    } else {
        $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: records.php");
    exit;
}

// Read what the user typed in the search box (empty if nothing)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Records - Hospital System</title>

    <!-- font awesome (for the menu icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- shared site stylesheet (for the header / navbar) -->
    <link rel="stylesheet" href="style.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        /* push the page content below the fixed header */
        .records-wrap { margin: 30px; padding-top: 8rem; color: #444d53; }
        .records-wrap h1 { color: #0188df; text-transform: capitalize; }
        .records-wrap h2 { color: #0188df; margin-top: 40px; text-transform: capitalize; }
        .records-wrap table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        .records-wrap th, .records-wrap td { border: 1px solid #ccc; padding: 10px 12px; text-align: left; }
        .records-wrap th { background: #0188df; color: #fff; text-transform: capitalize; }
        .records-wrap tr:nth-child(even) { background: #f4f8fc; }
        .records-wrap a { color: #0188df; }
        .searchbar input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .searchbar button { padding: 8px 14px; background: #0188df; color: #fff;
                            border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

    <!-- header section (same as the rest of the site) -->
    <header>
        <a href="home.html" class="logo"><span>H</span>ospital <span>C</span>ares.</a>
        <nav class="navbar">
            <ul>
                <li><a href="home.html">home</a></li>
                <li><a href="form.html">appointment</a></li>
                <li><a href="records.php">records</a></li>
                <li><a href="login.php">login</a></li>
            </ul>
        </nav>
        <div class="fas fa-bars"></div>
    </header>

    <div class="records-wrap">

    <h1>Patient &amp; Appointment Records</h1>
    <div class="searchbar">
    <form method="GET" action="records.php">
        <input type="text" name="search" placeholder="Search by name..."
               value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit">Search</button>
        <a href="records.php">Reset</a>
    </form>
</div>

    <h2>Patient Records</h2>
    <a href="add_patient.php" style="display:inline-block; margin-bottom:10px;
       padding:8px 14px; background:#0188df; color:#fff; text-decoration:none;
       border-radius:4px;">+ Add New Patient</a>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Age</th>
            <th>Gender</th><th>Contact</th><th>Email</th><th>Action</th>
        </tr>
        <?php
        // Ask the database for all patients
        // If user searched, filter by name; otherwise show everyone
        if ($search !== '') {
            $stmt = $conn->prepare("SELECT * FROM patients WHERE full_name LIKE ?");
            $like = "%" . $search . "%";
            $stmt->bind_param("s", $like);
            $stmt->execute();
            $patients = $stmt->get_result();
        } else {
            $patients = $conn->query("SELECT * FROM patients");
        }

        // Loop through each row and print it as a table row
        while ($row = $patients->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['patient_id'] . "</td>";
            echo "<td>" . $row['full_name'] . "</td>";
            echo "<td>" . $row['age'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td>" . $row['contact'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>
                    <a href='edit_patient.php?id=" . $row['patient_id'] . "'>Edit</a> |
                    <a href='records.php?delete=" . $row['patient_id'] . "&type=patient'
                       onclick=\"return confirm('Delete this patient?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
       ?>
    </table>

    <h2>Appointment Records</h2>
    <a href="add_appointment.php" style="display:inline-block; margin-bottom:10px;
       padding:8px 14px; background:#0188df; color:#fff; text-decoration:none;
       border-radius:4px;">+ Add New Appointment</a>

    <table>
        <tr>
            <th>ID</th><th>Patient ID</th><th>Doctor</th>
            <th>Date</th><th>Time</th><th>Status</th><th>Action</th>
        </tr>
        <?php
        // Ask the database for all appointments
        // If user searched, filter appointments too
        if ($search !== '') {
            $stmt = $conn->prepare("SELECT * FROM appointments WHERE doctor_name LIKE ? OR status LIKE ?");
            $like = "%" . $search . "%";
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $appts = $stmt->get_result();
        } else {
            $appts = $conn->query("SELECT * FROM appointments");
        }

        // Loop through each appointment row
        while ($row = $appts->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['appointment_id'] . "</td>";
            echo "<td>" . $row['patient_id'] . "</td>";
            echo "<td>" . $row['doctor_name'] . "</td>";
            echo "<td>" . $row['appointment_date'] . "</td>";
            echo "<td>" . $row['appointment_time'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
                    <a href='edit_appointment.php?id=" . $row['appointment_id'] . "'>Edit</a> |
                    <a href='records.php?delete=" . $row['appointment_id'] . "&type=appointment'
                       onclick=\"return confirm('Delete this appointment?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Booking Requests (from Appointment Form)</h2>
    <table>
        <tr>
            <th>ID</th><th>Full Name</th><th>Email</th>
            <th>Phone</th><th>Message</th><th>Requested On</th><th>Action</th>
        </tr>
        <?php
        // Show all booking requests submitted through the appointment form.
        // If the user searched, filter these by name or email too.
        if ($search !== '') {
            $stmt = $conn->prepare("SELECT * FROM appointment_requests WHERE full_name LIKE ? OR email LIKE ?");
            $like = "%" . $search . "%";
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $requests = $stmt->get_result();
        } else {
            $requests = $conn->query("SELECT * FROM appointment_requests ORDER BY request_date DESC");
        }

        // Loop through each booking request and print it
        while ($row = $requests->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['request_id'] . "</td>";
            echo "<td>" . $row['full_name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone'] . "</td>";
            echo "<td>" . $row['message'] . "</td>";
            echo "<td>" . $row['request_date'] . "</td>";
            echo "<td>
                    <a href='records.php?delete=" . $row['request_id'] . "&type=request'
                       onclick=\"return confirm('Delete this booking request?')\">Delete</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

    </div><!-- /records-wrap -->

    <!-- jquery + navbar script (makes the menu icon work) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="app.js"></script>

</body>
</html>

