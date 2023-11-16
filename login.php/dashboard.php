<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['error_message'] = 'Bạn cần đăng nhập để truy cập trang này';
    header('Location: login.php'); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <!-- Các nội dung của trang dashboard -->
        <a href="products.php">Products</a>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>