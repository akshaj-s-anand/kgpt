<?php
include 'db.php'; // Include your database connection file

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch the form values
    $course_completion_year = $_POST['course_completion_year'];
    $course_type = $_POST['course_type'];
    $department_of_study = $_POST['department_of_study'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $contact_number = $_POST['contact_number'];
    $whatsApp_number = $_POST['whatsApp_number'];
    $current_job = $_POST['current_job'];
    $designation = $_POST['designation'];
    $company = $_POST['company'];
    $password = $_POST['password']; // Fetch password from 'password' field
    $userlevel = 'R'; // Set userlevel to 'R' by default

    // Assume $pdo is your PDO database connection

    // Get the maximum ID value from the usermanagement_user table
    $max_id_stmt = $pdo->query("SELECT MAX(id) AS max_id FROM usermanagement_user");
    $max_id_row = $max_id_stmt->fetch(PDO::FETCH_ASSOC);
    $max_id = $max_id_row['max_id'];

    // Calculate the next ID value
    $next_id = $max_id + 1;

    // Format the ID with leading zeros if necessary
    $formatted_id = sprintf('%04d', $next_id);

    // Now you can use $formatted_id in your username calculation


    // Calculate the username based on the format
    $username = substr($department_of_study, -2) . $course_completion_year . 'R' . $formatted_id;

    // Insert new data into the database
    $stmt = $pdo->prepare("INSERT INTO usermanagement_user (username, course_completion_year, course_type, department_of_study, name, email, address, birth_date, gender, blood_group, contact_number, whatsApp_number, current_job, designation, company, password, userlevel) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    try {
        if ($stmt->execute([$username, $course_completion_year, $course_type, $department_of_study, $name, $email, $address, $birth_date, $gender, $blood_group, $contact_number, $whatsApp_number, $current_job, $designation, $company, $password, $userlevel])) {
            $message = 'Registration successful!';
            header('Location: success.php?message=' . urlencode($message)); // Redirect to success page with message
            exit();
        } else {
            $message = 'Registration failed. Please try again.';
        }
    } catch (PDOException $e) {
        $message = 'An error occurred: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body onload="generatePassword()">
    <center>
        <h2>User Registration</h2>
    </center>

    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" action="register.php">
        <!-- (your existing form fields) -->

        Course Completion Year:<span class="red">*</span>
        <select name="course_completion_year" required>
            <?php
            $currentYear = date("Y"); // Get the current year
            $startYear = 1945; // Start year

            // Loop to generate options from 1945 to current year
            for ($year = $currentYear; $year >= $startYear; $year--) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
        </select><br>
        Course Type:<span class="red">*</span>
        <select name="course_type" required>
            <option value="Regular">Regular</option>
            <option value="Part-time">Part-time</option>
        </select><br>

        Department of Study:<span class="red">*</span>
        <select name="department_of_study" required>
            <option value="CE">Civil - CE</option>
            <option value="ME">Mechanical - ME</option>
            <option value="EE">Electrical - EE</option>
            <option value="CH">Chemical - CH</option>
            <option value="CT">Computer - CT</option>
            <option value="TD">Tool and Die - TD</option>
            <option value="OT">Other - OT</option>
        </select><br>

        Name:<span class="red">*</span>
        <input type="text" name="name" required><br>

        Email:<span class="red">*</span>
        <input type="email" name="email" required><br>

        Address:<span class="red">*</span>
        <input type="text" name="address" required><br>

        Birth Date:<span class="red">*</span> <input type="date" name="birth_date" required><br>

        Gender:<span class="red">*</span>
        <select name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Others">Others</option>
        </select><br>

        Blood Group:<span class="red">*</span>
        <select name="blood_group" required>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="Rh+">Rh+</option>
            <option value="Rh-">Rh-</option>
        </select><br>

        Contact Number:<span class="red">*</span>
        <input type="text" name="contact_number" id="contact_number" pattern="[0-9]+" title="Please enter only numbers" required>
        <br>
        <div class="d-flex">
        <input type="checkbox" id="same_as_contact" onclick="setWhatsAppNumber()" style="width: 20px;">
        <label for="same_as_contact">WhatsApp Number same as Contact Number</label>
        </div>
        <br>
        
        WhatsApp Number:<span class="red">*</span>
        <input type="text" name="whatsApp_number" id="whatsApp_number" pattern="[0-9]+" title="Please enter only numbers" required>
        
        <br>

        Current Job:<span class="red">*</span> <input type="text" name="current_job" required><br>
        Designation:<span class="red">*</span> <input type="text" name="designation" required><br>
        Company:<span class="red">*</span> <input type="text" name="company" required><br>
        <input type="hidden" name="password" id="password">
        <input type="submit" value="Register">
    </form>

    <script>
        function setWhatsAppNumber() {
            if (document.getElementById('same_as_contact').checked) {
                document.getElementById('whatsApp_number').value = document.getElementById('contact_number').value;
            } else {
                document.getElementById('whatsApp_number').value = '';
            }
        }

        function generatePassword() {
            var length = 10,
                charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
                password = "";

            for (var i = 0; i < length; i++) {
                var char = charset.charAt(Math.floor(Math.random() * charset.length));
                password += char;
            }

            document.getElementById('password').value = password;
        }
    </script>

</body>
</html>
