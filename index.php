<?php
require_once 'util/pdo.php';
require_once 'util/utils.php';

$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM Profile");
$profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Name's Profile Database</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Profile Database</h1>
        <?php flashMessage(); ?>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Welcome <?= htmlentities($_SESSION['name']) ?> 
                <a href="logout.php">Logout</a>
            </p>
            <a href="add.php" class="btn">Add New Entry</a>
        <?php else: ?>
            <p><a href="login.php">Please log in</a></p>
        <?php endif; ?>
        
        <h2>Profiles</h2>
        <?php if (count($profiles) > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Headline</th>
                    <th>Action</th>
                </tr>
                <?php foreach($profiles as $profile): ?>
                    <tr>
                        <td>
                            <a href="view.php?profile_id=<?= $profile['profile_id'] ?>">
                                <?= htmlentities($profile['first_name'] . ' ' . $profile['last_name']) ?>
                            </a>
                        </td>
                        <td><?= htmlentities($profile['headline']) ?></td>
                        <td>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="edit.php?profile_id=<?= $profile['profile_id'] ?>" class="btn">Edit</a>
                                <a href="delete.php?profile_id=<?= $profile['profile_id'] ?>" class="btn btn-danger">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No profiles found</p>
        <?php endif; ?>
    </div>
</body>
</html>