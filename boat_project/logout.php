<?php
// In logout.php
session_start();
session_destroy();
header("Location: login.php?message=logged_out");
exit();
?>

<!-- In login.php -->
<?php if (isset($_GET['message']) && $_GET['message'] == 'logged_out') : ?>
    <p>You have successfully logged out.</p>
<?php endif; ?>
