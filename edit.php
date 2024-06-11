<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch the form values
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $birth_date = $_POST['birth_date'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $blood_group = $_POST['blood_group'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $whatsApp_number = $_POST['whatsApp_number'] ?? '';
    $current_job = $_POST['current_job'] ?? '';
    $designation = $_POST['designation'] ?? '';
    $company = $_POST['company'] ?? '';
    $username = $_POST['username'] ?? '';
    $userlevel = $_POST['userlevel'] ?? '';
    $department_of_study = $_POST['department_of_study'] ?? '';
    $password = $_POST['password'] ?? ''; // New password field

    // Update the user data in the database
    $stmt = $pdo->prepare("UPDATE usermanagement_user SET name=?, email=?, address=?, birth_date=?, gender=?, blood_group=?, contact_number=?, whatsApp_number=?, current_job=?, designation=?, company=?, username=?, userlevel=?, department_of_study=?, password=? WHERE id=?");

    try {
        // Logic to update the 7th character of the username based on userlevel change
        if ($userlevel == 'L') {
            $username[6] = 'L';
        } elseif ($userlevel == 'R') {
            $username[6] = 'R';
        }

        if ($stmt->execute([$name, $email, $address, $birth_date, $gender, $blood_group, $contact_number, $whatsApp_number, $current_job, $designation, $company, $username, $userlevel, $department_of_study, $password, $id])) {
    // Check if user level is 'L'
    if ($userlevel == 'L') {
        // Send email with updated username and password
        $subject = 'Your Account Information has been Updated';
        $message = "Hello $name,\n\nYour account information has been updated successfully!\n\nUsername: $username\nPassword: $password\n\nBest regards,\nYour Website Team";
        $headers = 'From: Your Website <info@kgptalumni.in>';

        // Replace "admin@example.com" with your actual admin email address
        mail($email, $subject, $message, $headers);
    }

    $message = 'User details updated successfully!';
    header('Location: updatesuccess.php?message=' . urlencode($message)); // Redirect to updatesuccess page with message
    exit();
} else {
    $message = 'Failed to update user details. Please try again.';
}
    } catch (PDOException $e) {
        $message = 'An error occurred: ' . $e->getMessage();
    }
}

// Fetch user data based on ID from URL parameter
$user = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM usermanagement_user WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Add your styles here */
    </style>
    <script>
        function setWhatsAppNumber() {
            if (document.getElementById('same_as_contact').checked) {
                document.getElementById('whatsApp_number').value = document.getElementById('contact_number').value;
            }
        }
    </script>
</head>

<body>

    <div class="container">
        <h2>Edit User</h2>

        <?php if ($message) : ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <a href="./dashboard.php" class="go-back-btn">Go Back</a>

        <form method="post" action="edit.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id'] ?? ''); ?>">

            Course Completion Year:
            <select name="course_completion_year">
                <?php
                $currentYear = date("Y");
                $startYear = 1945;

                for ($year = $currentYear; $year >= $startYear; $year--) {
                    $selected = ($year == ($user['course_completion_year'] ?? '')) ? 'selected' : '';
                    echo "<option value='$year' $selected>$year</option>";
                }
                ?>
            </select><br>

            Course Type:
            <select name="course_type">
                <option value="Regular" <?php echo ($user['course_type'] ?? '') == 'Regular' ? 'selected' : ''; ?>>Regular</option>
                <option value="Part-time" <?php echo ($user['course_type'] ?? '') == 'Part-time' ? 'selected' : ''; ?>>Part-time</option>
            </select><br>

            Department of Study:
            <select name="department_of_study">
                <?php
                $departments = ['CE', 'ME', 'EE', 'CH', 'CT', 'TD', 'OT'];
                foreach ($departments as $dept) {
                    $selected = ($dept == ($user['department_of_study'] ?? '')) ? 'selected' : '';
                    echo "<option value='$dept' $selected>$dept</option>";
                }
                ?>
            </select><br>
            Username:
            <input type="text" name="username" value="<?php echo htmlspecialchars($username ?? $user['username'] ?? ''); ?>"><br>


            Name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"><br>

            Email:
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"><br>

            Address:
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"><br>

            Birth Date:
            <input type="date" name="birth_date" value="<?php echo htmlspecialchars($user['birth_date'] ?? ''); ?>"><br>

            Gender:
            <select name="gender">
                <option value="Male" <?php echo ($user['gender'] ?? '') == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($user['gender'] ?? '') == 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Others" <?php echo ($user['gender'] ?? '') == 'Others' ? 'selected' : ''; ?>>Others</option>
            </select><br>

            Blood Group:
            <select name="blood_group">
                <?php
                $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Rh+', 'Rh-'];
                foreach ($bloodGroups as $group) {
                    $selected = ($group == ($user['blood_group'] ?? '')) ? 'selected' : '';
                    echo "<option value='$group' $selected>$group</option>";
                }
                ?>
            </select><br>

            Contact Number:
            <input type="text" name="contact_number" id="contact_number" pattern="[0-9]+" title="Please enter only numbers" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>"><br>

            <input type="checkbox" id="same_as_contact" onclick="setWhatsAppNumber()">
            <label for="same_as_contact">WhatsApp Number same as Contact Number</label><br>

            WhatsApp Number:
            <input type="text" name="whatsApp_number" id="whatsApp_number" pattern="[0-9]+" title="Please enter only numbers" value="<?php echo htmlspecialchars($user['whatsApp_number'] ?? ''); ?>"><br>

            Current Job:
            <input type="text" name="current_job" value="<?php echo htmlspecialchars($user['current_job'] ?? ''); ?>"><br>

            Designation:
            <input type="text" name="designation" value="<?php echo htmlspecialchars($user['designation'] ?? ''); ?>"><br>

            Company:
            <input type="text" name="company" value="<?php echo htmlspecialchars($user['company'] ?? ''); ?>"><br>

            User Level:
            <select name="userlevel">
                <option value="R" <?php echo ($user['userlevel'] ?? '') == 'R' ? 'selected' : ''; ?>>Regular</option>
                <option value="L" <?php echo ($user['userlevel'] ?? '') == 'L' ? 'selected' : ''; ?>>Lifetime</option>
            </select><br>
            New Password:
            <input type="password" name="password"><br>


            <input type="submit" value="Update">
        </form>

    </div>

</body>

</html>