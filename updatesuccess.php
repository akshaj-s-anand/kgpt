<?php include "db.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Successful</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h2 class="success-heading">Update Successful</h2>
    <p class="success-message"><?php echo isset($_GET['message']) ? $_GET['message'] : 'User details updated successfully!'; ?></p>
    <a href="./dashboard.php" class="btn-green">Go back</a>
  </div>
</body>
</html>
