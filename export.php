<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch users from database
$stmt = $pdo->prepare("SELECT * FROM usermanagement_user");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define the CSV file name
$filename = "users_export.csv";

// Set headers to download file rather than display
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, ['Sl. No.', 'Name', 'Email','Address','WhatsApp Number' ,'Course Type','Current Job','Company','Password','Designation','Birth Date','Course Completion Year', 'Dept. Of Study', 'Username', 'User Level', 'Contact Number', 'Blood Group', 'Gender']);

// Loop through the data and write to output
foreach ($users as $index => $user) {
    fputcsv($output, [
        $index + 1,
        $user['name'],
        $user['email'],
        $user['course_completion_year'],
        $user['department_of_study'],
        $user['username'],
        $user['userlevel'],
        $user['address'],
        $user['gender'],
        $user['whatsApp_number'],
        $user['current_job'],
        $user['company'],
        $user['password'],
        $user['designation'],
        $user['birth_date'],
        $user['contact_number'],
        $user['blood_group'],
        $user['course_type'],
    ]);
}

// Close the output stream
fclose($output);
exit();
?>
