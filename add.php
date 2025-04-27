<?php
require_once 'util/pdo.php';
require_once 'util/utils.php';

// Check login
checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate profile data
    $validation = validateProfile();
    
    if ($validation !== true) {
        $_SESSION['error'] = $validation;
        header("Location: add.php");
        return;
    }
    
    // Insert profile
    $stmt = $pdo->prepare('INSERT INTO Profile 
        (user_id, first_name, last_name, email, headline, summary) 
        VALUES (:uid, :fn, :ln, :em, :he, :su)');
    
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']
    ));
    
    $_SESSION['success'] = "Profile added";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Name's Add Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Profile</h1>
        <?php flashMessage(); ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </div>
            <div class="form-group">
                <label for="headline">Headline</label>
                <input type="text" name="headline" id="headline">
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea name="summary" id="summary" rows="5"></textarea>
            </div>
            <input type="submit" value="Add" class="btn">
            <a href="index.php" class="btn btn-back">Cancel</a>
        </form>
    </div>
</body>
</html>