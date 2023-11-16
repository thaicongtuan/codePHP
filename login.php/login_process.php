<?php
session_start();
require('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Kiểm tra mật khẩu sử dụng hàm password_verify
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header('Location: products.php'); // Chuyển hướng sau khi đăng nhập thành công
            exit();
        } else {
            $_SESSION['error_message'] = 'Invalid username or password';
            header('Location: login.php'); // Chuyển hướng về trang đăng nhập khi đăng nhập thất bại
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Invalid username or password';
        header('Location: login.php'); // Chuyển hướng về trang đăng nhập khi đăng nhập thất bại
        exit();
    }
}
?>