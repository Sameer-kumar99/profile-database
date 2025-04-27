<?php
session_start();

// Flash message handling
function flashMessage() {
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . '</p>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color: green;">' . htmlentities($_SESSION['success']) . '</p>';
        unset($_SESSION['success']);
    }
}

// Check if user is logged in
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        die("Not logged in");
    }
}

// Validate profile data
function validateProfile() {
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || 
        strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || 
        strlen($_POST['summary']) < 1) {
        return "All fields are required";
    }
    
    if (strpos($_POST['email'], '@') === false) {
        return "Email address must contain @";
    }
    
    return true;
}

// Check if user owns the profile
function checkProfileOwner($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT user_id FROM Profile WHERE profile_id = :pid');
    $stmt->execute(array(':pid' => $profile_id));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = "Profile not found";
        header("Location: index.php");
        return false;
    }
    
    if ($row['user_id'] != $_SESSION['user_id']) {
        $_SESSION['error'] = "You don't have permission to edit this profile";
        header("Location: index.php");
        return false;
    }
    
    return true;
}
?>