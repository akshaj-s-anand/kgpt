<?php
include 'db.php'; // Include your database connection file

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user details from the database based on the username and user level other than 'R'
    $stmt = $pdo->prepare("SELECT * FROM usermanagement_user WHERE username = ? AND userlevel != 'R'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        // Password is correct, set session variables
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['userlevel'] = $user['userlevel']; // Assuming you used 'type' column for user level

        // Redirect to dashboard for non-'R' users
        if ($_SESSION['userlevel'] != 'R') {
            header('Location: dashboard.php');
            exit();
        } else {
            // Redirect 'R' users to the upgrade_plan.php page
            header('Location: upgrade_plan.php');
            exit();
        }
    } else {
        $message = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>

    <center>
        <h2>Login</h2>
    </center>

    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post" action="login.php">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>

</body>

</html>
