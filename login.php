<?php
ob_start(); // Ngăn chặn lỗi headers already sent
require_once 'inc/database.php';

_header("Login - Otaku Realm");
navbar();
login();
_footer();

ob_end_flush();
?>