<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$userlevel = $_SESSION['userlevel']; // Corrected session variable name
$user_id = $_SESSION['user_id']; // Store the logged-in user's ID

// Define user level labels based on your system
$userLevelLabels = [
    'R' => 'R',
    'L' => 'L',
    // Add other user levels as needed
];

$userLevelLabel = isset($userLevelLabels[$userlevel]) ? $userLevelLabels[$userlevel] : 'Unknown';

// Fetch users from database
$stmt = $pdo->prepare("SELECT * FROM usermanagement_user");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch unique filter values
$course_completion_years = array_unique(array_column($users, 'course_completion_year'));
$userlevels = array_unique(array_column($users, 'userlevel'));
$blood_groups = array_unique(array_column($users, 'blood_group'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Welcome to Admin Dashboard</h2>
            <div class="user-info">
                <p>Welcome: <?php echo $username; ?></p>
                <a href="logout.php" class="logout-btn">Logout</a>
                <a href="edit.php?id=<?php echo $user_id; ?>" class="edit-profile-btn">Edit Profile</a> <!-- Edit Profile Button -->
            </div>
        </div>

        <div class="content">
            <h3>User Management</h3>
            <p>Here are the users registered in the system:</p>

            <div>
                <label for="filter-course_completion_year">Course Completion Year: </label>
                <select id="filter-course_completion_year">
                    <option value="">All</option>
                    <?php foreach ($course_completion_years as $year) : ?>
                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="filter-userlevel">User Level: </label>
                <select id="filter-userlevel">
                    <option value="">All</option>
                    <?php foreach ($userlevels as $level) : ?>
                        <option value="<?php echo $level; ?>"><?php echo $level; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="filter-blood_group">Blood Group: </label>
                <select id="filter-blood_group">
                    <option value="">All</option>
                    <?php foreach ($blood_groups as $group) : ?>
                        <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="filter-gender">Gender: </label>
                <select id="filter-gender">
                    <option value="">All</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <!-- Add more gender options if necessary -->
                </select>
            </div>

            <table border="1" id="userTable">
                <thead>
                    <tr>
                        <th>Sl. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course Completion Year</th>
                        <th>Username</th>
                        <th>User Level</th>
                        <th>Contact Number</th>
                        <th>Blood Group</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $index => $user) : ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['course_completion_year']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['userlevel']; ?></td>
                            <td><?php echo $user['contact_number']; ?></td>
                            <td><?php echo $user['blood_group']; ?></td>
                            <td><?php echo $user['gender']; ?></td>
                            <td class="action-icons">
                                <?php if ($userlevel != 'L') : ?>
                                    <a href="edit.php?id=<?php echo $user['id']; ?>" class="edit-btn"><i class="fas fa-edit color-blue"></i></a>
                                <?php endif; ?>
                                <?php if ($userlevel != 'L' && $user['userlevel'] != 'User') : ?>
                                    <a href="delete.php?id=<?php echo $user['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash-alt color-red"></i></a>
                                <?php endif; ?>
                                <a href="user_details.php?id=<?php echo $user['id'] ?>" class="view-btn"><i class="fa-regular fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Add more content and features as needed -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#userTable').DataTable();

            $('#filter-course_completion_year, #filter-userlevel, #filter-blood_group, #filter-gender').on('change', function() {
                var course_completion_year = $('#filter-course_completion_year').val();
                var userlevel = $('#filter-userlevel').val();
                var blood_group = $('#filter-blood_group').val();
                var gender = $('#filter-gender').val();

                table.column(3).search(course_completion_year).draw();
                table.column(5).search(userlevel).draw();
                table.column(7).search(blood_group).draw();
                table.column(8).search(gender).draw();
            });
        });
    </script>
</body>
</html>
