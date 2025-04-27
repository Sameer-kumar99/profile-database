<?php
require_once 'util/pdo.php';
require_once 'util/utils.php';

// Check login
checkLogin();

// Make sure profile_id is specified
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
}

// Check if user owns the profile
if (!checkProfileOwner($pdo, $_GET['profile_id'])) {
    return;
}

// Handle POST request (actual delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('DELETE FROM Profile WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(
        ':pid' => $_POST['profile_id'],
        ':uid' => $_SESSION['user_id']
    ));
    
    $_SESSION['success'] = "Profile deleted";
    header("Location: index.php");
    return;
}

// Get profile data for confirmation
$stmt = $pdo->prepare("SELECT first_name, last_name FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(':pid' => $_GET['profile_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Name's Delete Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Delete Profile</h1>
        <p>Are you sure you want to delete the profile for <?= htmlentities($profile['first_name'] . ' ' . $profile['last_name']) ?>?</p>
        
        <form method="POST">
            <input type="hidden" name="profile_id" value="<?= $_GET['profile_id'] ?>">
            <input type="submit" value="Delete" class="btn btn-danger">
            <a href="index.php" class="btn btn-back">Cancel</a>
        </form>
    </div>
</body>
</html>