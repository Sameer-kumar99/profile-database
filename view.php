<?php
require_once 'util/pdo.php';
require_once 'util/utils.php';

// Make sure profile_id is specified
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
}

// Get profile data
$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(':pid' => $_GET['profile_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if ($profile === false) {
    $_SESSION['error'] = "Profile not found";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Name's View Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Profile Information</h1>
        <p>First Name: <?= htmlentities($profile['first_name']) ?></p>
        <p>Last Name: <?= htmlentities($profile['last_name']) ?></p>
        <p>Email: <?= htmlentities($profile['email']) ?></p>
        <p>Headline: <?= htmlentities($profile['headline']) ?></p>
        <p>Summary: <?= htmlentities($profile['summary']) ?></p>
        
        <a href="index.php" class="btn btn-back">Back to profiles</a>
    </div>
</body>
</html>