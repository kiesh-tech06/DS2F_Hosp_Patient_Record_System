<?php
// Bring in the database connection we made earlier
include 'db_connect.php';

// If a delete was requested, remove that row first
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = intval($_GET['delete']);
    if ($_GET['type'] === 'appointment') {
        $stmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id = ?");
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        body { font-family: "Roboto", Arial, sans-serif; margin: 30px; color: #444d53; }
        h1 { color: #0188df; text-transform: capitalize; }
        h2 { color: #0188df; margin-top: 40px; text-transform: capitalize; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 10px 12px; text-align: left; }
        th { background: #0188df; color: #fff; text-transform: capitalize; }
        tr:nth-child(even) { background: #f4f8fc; }
        a { color: #0188df; }
        .searchbar input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .searchbar button { padding: 8px 14px; background: #0188df; color: #fff;
                            border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

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

</body>
</html>