<?php
include 'db_connect.php';

$id = intval($_GET['id']);   // which patient are we editing?

// If the form was submitted, save the changes (UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = $_POST['full_name'];
    $age     = intval($_POST['age']);
    $gender  = $_POST['gender'];
    $contact = $_POST['contact'];
    $email   = $_POST['email'];

    $stmt = $conn->prepare(
        "UPDATE patients SET full_name=?, age=?, gender=?, contact=?, email=? WHERE patient_id=?"
    );
    $stmt->bind_param("sisssi", $name, $age, $gender, $contact, $email, $id);
    $stmt->execute();

    header("Location: records.php");   // done, go back to the list
    exit;
}

// Otherwise, fetch the current details to fill the form
$stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();

if (!$patient) {
    die("Patient not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Patient</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { padding: 8px; width: 280px; }
        button { margin-top: 20px; padding: 10px 18px; }
    </style>
</head>
<body>
    <h1>Edit Patient Record</h1>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?php echo $patient['full_name']; ?>" required>

        <label>Age</label>
        <input type="number" name="age" value="<?php echo $patient['age']; ?>" required>

        <label>Gender</label>
        <select name="gender">
            <option <?php if ($patient['gender']=='Male')   echo 'selected'; ?>>Male</option>
            <option <?php if ($patient['gender']=='Female') echo 'selected'; ?>>Female</option>
        </select>

        <label>Contact</label>
        <input type="text" name="contact" value="<?php echo $patient['contact']; ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?php echo $patient['email']; ?>">

        <button type="submit">Save Changes</button>
        <a href="records.php">Cancel</a>
    </form>
</body>
</html>