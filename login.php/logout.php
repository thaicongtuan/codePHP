<?php
session_start();

// Hủy tất cả các biến trong phiên
$_SESSION = array();

// Hủy bỏ phiên hiện tại
session_destroy();

// Xác định thông tin liên quan đến cookie của phiên hiện tại
$session_name = session_name();
$session_cookie_params = session_get_cookie_params();
$session_path = $session_cookie_params['path'];
$session_domain = $session_cookie_params['domain'];
$session_secure = $session_cookie_params['secure'];
$session_httponly = $session_cookie_params['httponly'];

// Hủy bỏ cookie của phiên hiện tại
setcookie(
    $session_name,
    '',
    time() - 3600,
    $session_path,
    $session_domain,
    $session_secure,
    $session_httponly
);

// Chuyển hướng về trang đăng nhập sau khi đăng xuất
header('Location: login.php');
exit();
?>