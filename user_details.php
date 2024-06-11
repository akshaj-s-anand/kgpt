<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your custom CSS file -->
</head>

<body>
    <div class="container">
        <h2>User Details</h2>
        <table>
            <tbody>
                <?php
                include 'db.php';

                if (isset($_GET['id'])) {
                    $id = $_GET['id'];

                    // Fetch user data based on ID from URL parameter
                    $stmt = $pdo->prepare("SELECT * FROM usermanagement_user WHERE id = ?");
                    $stmt->execute([$id]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Go back button
                    echo "<tr><td colspan='2'><a href='./dashboard.php' class='go-back-btn'>Go Back</a></td></tr>";
                    if ($user) {
                        echo "<tr><th>ID</th><td>{$user['id']}</td></tr>";
                        echo "<tr><th>Course Completion Year</th><td>{$user['course_completion_year']}</td></tr>";
                        echo "<tr><th>Course Type</th><td>{$user['course_type']}</td></tr>";
                        echo "<tr><th>Department of Study</th><td>{$user['department_of_study']}</td></tr>";
                        echo "<tr><th>Name</th><td>{$user['name']}</td></tr>";
                        echo "<tr><th>Email</th><td>{$user['email']}</td></tr>";
                        echo "<tr><th>Address</th><td>{$user['address']}</td></tr>";
                        echo "<tr><th>Birth Date</th><td>{$user['birth_date']}</td></tr>";
                        echo "<tr><th>Gender</th><td>{$user['gender']}</td></tr>";
                        echo "<tr><th>Blood Group</th><td>{$user['blood_group']}</td></tr>";
                        echo "<tr><th>Contact Number</th><td>{$user['contact_number']}</td></tr>";
                        echo "<tr><th>WhatsApp Number</th><td>{$user['whatsApp_number']}</td></tr>";
                        echo "<tr><th>Current Job</th><td>{$user['current_job']}</td></tr>";
                        echo "<tr><th>Designation</th><td>{$user['designation']}</td></tr>";
                        echo "<tr><th>Company</th><td>{$user['company']}</td></tr>";
                    } else {
                        echo "<tr><td colspan='2'>User not found</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>User ID not provided</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>