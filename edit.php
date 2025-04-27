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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate profile data
    $validation = validateProfile();
    
    if ($validation !== true) {
        $_SESSION['error'] = $validation;
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }
    
    // Update profile
    $stmt = $pdo->prepare('UPDATE Profile 
        SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su 
        WHERE profile_id = :pid AND user_id = :uid');
    
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':pid' => $_POST['profile_id'],
        ':uid' => $_SESSION['user_id']
    ));
    
    $_SESSION['success'] = "Profile updated";
    header("Location: index.php");
    return;
}

// Get profile data
$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(':pid' => $_GET['profile_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Name's Edit Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <?php flashMessage(); ?>
        
        <form method="POST">
            <input type="hidden" name="profile_id" value="<?= $profile['profile_id'] ?>">
            
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?= htmlentities($profile['first_name']) ?>">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="<?= htmlentities($profile['last_name']) ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?= htmlentities($profile['email']) ?>">
            </div>
            <div class="form-group">
                <label for="headline">Headline</label>
                <input type="text" name="headline" id="headline" value="<?= htmlentities($profile['headline']) ?>">
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea name="summary" id="summary" rows="5"><?= htmlentities($profile['summary']) ?></textarea>
            </div>
            <input type="submit" value="Save" class="btn">
            <a href="index.php" class="btn btn-back">Cancel</a>
        </form>
    </div>
</body>
</html>