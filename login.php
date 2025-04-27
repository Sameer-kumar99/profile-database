<?php
require_once 'util/pdo.php';
require_once 'util/utils.php';

if (isset($_POST['email']) && isset($_POST['pass'])) {
    $salt = 'XyZzy12*_';
    $check = hash('md5', $salt . $_POST['pass']);
    
    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        // Redirect the browser to index.php
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Incorrect email or password";
        header("Location: login.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Name's Login Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
    function doValidate() {
        console.log('Validating...');
        try {
            let email = document.getElementById('email').value;
            let pw = document.getElementById('pass').value;
            
            console.log("Validating email="+email+" pw="+pw);
            
            if (email == null || email == "" || pw == null || pw == "") {
                alert("Both fields must be filled out");
                return false;
            }
            
            if (email.indexOf('@') === -1) {
                alert("Email address must contain @");
                return false;
            }
            
            return true;
        } catch(e) {
            console.log("Validation error: "+e);
            return false;
        }
        return false;
    }
    </script>
</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php flashMessage(); ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" name="pass" id="pass">
            </div>
            <input type="submit" value="Log In" onclick="return doValidate();" class="btn">
            <a href="index.php" class="btn btn-back">Cancel</a>
        </form>
    </div>
</body>
</html>