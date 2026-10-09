<?php
ob_start(); // Bật đệm đầu ra để tránh lỗi headers already sent
require_once 'inc/database.php';

// Xử lý logic đăng ký và kiểm tra lỗi trước khi in header HTML
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $errName = $errEmail = $errPhone = $errPwd = $errRepwd = '';
    
    if(empty($_POST['name'])) $errName = 'Name is not empty!';
    if(empty($_POST['email'])) $errEmail = 'Email is not empty!';
    else {
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            $errEmail = 'Email is invalid!';
    }

    if(empty($_POST['phone'])) $errPhone = 'Phone is not empty!';
    else {
        if(!preg_match('/^[0-9]{10}+$/', $_POST['phone']))
            $errPhone = 'Phone has 10 digits!';
    }

    if(empty($_POST['pwd'])) $errPwd = 'Password is not empty!';
    if(empty($_POST['repwd'])) $errRepwd = 'Repassword is not empty!';
    else {
        if($_POST['repwd'] != $_POST['pwd'])
            $errRepwd = 'Repassword not match!';
    }

    if($errName == '' && $errEmail == '' && $errPhone == '' && $errPwd == '' && $errRepwd == ''){
        $q = Database::query("INSERT INTO users(name, email, phone, password, role) 
        VALUES('".$_POST['name']."', '".$_POST['email']."', '".$_POST['phone']."', '".$_POST['pwd']."', 'user')");
        
        $q = Database::query("SELECT * FROM users WHERE email='".$_POST['email']."' AND password='".$_POST['pwd']."'");
        $_SESSION['user'] = $q->fetch_array();
        header('location: index.php');
        exit();
    }
}

_header("Register - Otaku Realm");
navbar();

// Hiển thị form đăng ký (gọi hoặc viết trực tiếp HTML form)
register(); 

_footer();
ob_end_flush();
?>