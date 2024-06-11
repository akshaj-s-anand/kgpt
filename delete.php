<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user data based on ID from URL parameter
    $stmt = $pdo->prepare("SELECT * FROM usermanagement_user WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
            // Proceed with deletion
            $stmt = $pdo->prepare("DELETE FROM usermanagement_user WHERE id = ?");
            $stmt->execute([$id]);
            $message = 'User deleted successfully!';
            header('Location: dashboard.php?message=' . urlencode($message));
            exit();
        }
    } else {
        $message = 'User not found';
        header('Location: dashboard.php?error=' . urlencode($message));
        exit();
    }
} else {
    $message = 'User ID not provided';
    header('Location: dashboard.php?error=' . urlencode($message));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delete User</title>
<link rel="stylesheet" href="styles.css"> <!-- Link your custom CSS file -->
</head>
<body>
  <div class="container">
    <h2>Delete User</h2>
    <p>Are you sure you want to delete this user?</p>
    <form action="delete.php?id=<?php echo $user['id']; ?>" method="POST">
      <input type="hidden" name="confirm" value="yes">
      <button type="submit" class="confirm-delete-btn">Delete</button>
      <a href="dashboard.php" class="cancel-delete-btn">Cancel</a>
    </form>
  </div>
</body>
</html>
