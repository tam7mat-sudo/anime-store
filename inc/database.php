<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Database {
    static $con;
    public static function getConnection() {
        if (self::$con == null)
            self::$con = new mysqli("localhost", "root", "", "anime_db");
        return self::$con;
    }
    public static function query($s) {
        return self::getConnection()->query($s);
    }
}

// Khai báo lớp Cart chuẩn phong cách thầy giáo
class Cart {
    public $id, $name, $image, $price, $quantity;
    function __construct($id, $name, $image, $price, $quantity){
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}

// Hàm thêm sản phẩm vào giỏ hàng qua session
function addProductToCart($id_product){
    $q = Database::query("select * from products where id=".$id_product);
    if($q && $r = $q->fetch_array()){
        if(isset($_SESSION['cart'])){
            $a = $_SESSION['cart'];
            $found = false;
            for($i = 0; $i < count($a); $i++){
                if($a[$i]->id == $r['id']){
                    $a[$i]->quantity++;
                    $found = true;
                    break;
                }
            }
            if(!$found){
                $a[count($a)] = new Cart($r['id'], $r['name'], $r['image'], $r['price'], 1);
            }
        } else {
            $a = array();
            $a[0] = new Cart($r['id'], $r['name'], $r['image'], $r['price'], 1);
        }
        $_SESSION['cart'] = $a;
    }
}

function _header($title) {
    $s = '
    <!DOCTYPE html>
    <html lang="vi">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'.$title.'</title>
    <!-- Load CSS Bootstrap 5 qua CDN ổn định -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #0f111a;
            color: #e2e8f0;
            font-family: "Segoe UI", Roboto, sans-serif;
        }
        .custom-nav {
            background-color: #181b2a !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .hero-banner {
            background: linear-gradient(135deg, #1e1b4b 0%, #311042 50%, #0f111a 100%);
            border-bottom: 2px solid #ff4655;
            box-shadow: 0 10px 30px rgba(255, 70, 85, 0.15);
        }
        .manga-card {
            background: #181b2a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .manga-card:hover {
            transform: translateY(-8px);
            border-color: #ff4655;
            box-shadow: 0 12px 25px rgba(255, 70, 85, 0.25);
        }
        .manga-img-container {
            position: relative;
            overflow: hidden;
            background-color: #0f111a;
        }
        .manga-card img {
            transition: transform 0.5s ease;
            width: 100%;
            display: block;
        }
        .manga-card:hover img {
            transform: scale(1.08);
        }
        .badge-hot {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #ff4655;
            color: #fff;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            z-index: 2;
        }
        .btn-manga {
            background: #ff4655;
            color: #fff;
            border: none;
            font-weight: 600;
            border-radius: 10px;
            transition: 0.2s;
        }
        .btn-manga:hover {
            background: #d90429;
            color: #fff;
        }
        a { text-decoration: none; }
    </style>
    </head>
    <body>';
    echo $s;
}

function _footer() {
    $s = '
    <footer class="mt-5 pt-5 text-white" style="background-color: #090a10; border-top: 1px solid rgba(255, 255, 255, 0.08)">
      <section class="container pb-4">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6">
            <h4 class="fw-bold text-danger mb-3"><i class="fa-solid fa-book-open-reader me-2"></i>OTAKU REALM</h4>
            <p class="text-secondary">Thiên đường truyện tranh Manga, Manhwa, Comics chính hãng độc quyền.</p>
          </div>
          <div class="col-lg-2 col-md-6">
            <h6 class="text-uppercase fw-bold text-light mb-3">Thể Loại Hot</h6>
            <ul class="list-unstyled text-secondary">
              <li class="mb-2"><a href="#" class="text-secondary">Shonen / Hành Động</a></li>
              <li class="mb-2"><a href="#" class="text-secondary">Hài Hước / Đời Thường</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6">
            <h6 class="text-uppercase fw-bold text-light mb-3">Dịch Vụ Độc Giả</h6>
            <ul class="list-unstyled text-secondary">
              <li class="mb-2"><i class="fa-solid fa-truck-fast me-2 text-danger"></i>Giao hàng hỏa tốc 2H</li>
              <li class="mb-2"><i class="fa-solid fa-gift me-2 text-danger"></i>Tặng kèm Bookmark cao cấp</li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6">
            <h6 class="text-uppercase fw-bold text-light mb-3">Liên Hệ</h6>
            <p class="text-secondary mb-2"><i class="fa-solid fa-location-dot me-2 text-danger"></i>Liên Chiểu Thành Phố Đà Nẵng</p>
            <p class="text-secondary"><i class="fa-solid fa-phone me-2 text-danger"></i>1900 6868</p>
          </div>
        </div>
      </section>
      <div class="text-center py-3 text-secondary" style="background-color: #050508; border-top: 1px solid rgba(255,255,255,0.05)">
        © 2026 Otaku Realm Store - Thế Giới Truyện Tranh Đẳng Cấp.
      </div>
    </footer>
    </body>
    </html>';
    echo $s;
}

function navbar() {
    if(isset($_GET['id_product'])) {
        addProductToCart($_GET['id_product']);
        // Chuyển hướng nhẹ nhàng để làm sạch URL, tránh lỗi F5 bị lặp sản phẩm
        header('location: index.php');
        exit();
    }
    if(isset($_GET['clear'])) {
        unset($_SESSION['cart']);
        header('location: cart.php');
        exit();
    }

    $totalQuantity = 0;
    if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $item){
            $totalQuantity += $item->quantity;
        }
    }

    $s = '
    <nav class="navbar navbar-expand-lg sticky-top navbar-dark custom-nav py-3">
      <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-white d-flex align-items-center" href="index.php">
          <img src="accets/icon/logo1.jpg" width="45" height="45">
          <span class="text-danger fw-black ms-2">OTAKU</span><span class="ms-1">REALM</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0 fw-semibold">
            <li class="nav-item">
              <a class="nav-link active text-white" href="index.php"><i class="fa-solid fa-house me-1"></i> Trang Chủ</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-secondary" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fa-solid fa-list me-1"></i> Danh Mục Truyện
              </a>
              <ul class="dropdown-menu dropdown-menu-dark border-secondary">';
                $q = Database::query("select * from categories");
                while ($r = $q->fetch_array()) {
                    $s .= '<li><a class="dropdown-item py-2" href="index.php?id_category='.$r['id'].'">📖 '.$r['name'].'</a></li>';
                }
              $s .= '</ul>
            </li>';

            if (!isset($_SESSION['user'])) {
                $s .= '<li class="nav-item"><a class="nav-link text-secondary" href="login.php"><i class="fa-solid fa-right-to-bracket me-1"></i> Đăng Nhập</a></li>';
            } else {
                $s .= '
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-warning fw-bold" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-circle-user me-1"></i> Hi, '.tachTen($_SESSION['user']['name']).'
                  </a>
                  <ul class="dropdown-menu dropdown-menu-dark border-secondary">
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-id-card me-2"></i>Hồ sơ độc giả</a></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-power-off me-2"></i>Đăng xuất</a></li>
                  </ul>
                </li>';
            }

        $s .= '
          </ul>
          <form class="d-flex position-relative me-3" role="search" action="index.php" method="get">
            <input class="form-control bg-dark text-white border-secondary rounded-pill ps-4 pe-5" type="search" placeholder="Tìm tên truyện..." />
            <button class="btn text-danger position-absolute end-0 top-0 mt-1 me-2 border-0 bg-transparent" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          </form>
          <a href="cart.php" class="btn btn-outline-danger rounded-pill px-3 position-relative">
            <i class="fa-solid fa-cart-shopping"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">'.$totalQuantity.'</span>
          </a>
        </div>
      </div>
    </nav>';
    echo $s;
}

function jumbotron() {
    $s = '
    <div class="hero-banner py-5 mb-4">
      <div class="container py-4 text-center text-lg-start">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase mb-3"><i class="fa-solid fa-fire me-1"></i> Siêu Siêu Hot 2026</span>';
            if (!isset($_GET['id_category'])) {
                $s .= '<h1 class="display-4 fw-black text-white mb-3">Tủ Sách Truyện Tranh <br><span class="text-danger">Độc Quyền & Đẳng Cấp</span></h1>';
            } else {
                $q = Database::query("select * from categories where id=".intval($_GET['id_category']));
                $catData = $q->fetch_array();
                $catName = $catData ? $catData['name'] : 'Danh Mục';
                $s .= '<h1 class="display-4 fw-black text-white mb-3">Bộ Sưu Tập: <span class="text-danger">'.$catName.'</span></h1>';
            }
            $s .= '<p class="lead text-secondary mb-4">Sở hữu trọn bộ Manga kinh điển, hình ảnh siêu sắc nét!</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
              <a href="#manga-list" class="btn btn-manga btn-lg px-4 py-2"><i class="fa-solid fa-compass me-2"></i>Khám Phá Ngay</a>
            </div>
          </div>
          <div class="col-lg-4 d-none d-lg-block text-center">
          </div>
        </div>
      </div>
    </div>';
    echo $s;
}

function body() {
    $s = '<div id="manga-list">';
    
    if (!isset($_GET['id_category'])) {
        $q = Database::query("select * from categories");
    } else {
        $q = Database::query("select * from categories where id=".intval($_GET['id_category']));
    }

    if ($q) {
        while ($r = $q->fetch_array()) {
            $categoryId = $r['id'];
            $categoryName = $r['name'];
            
            $q1 = Database::query("select * from products where id_category = $categoryId");

            if ($q1 && $q1->num_rows > 0) {
                $s .= '
                <section class="container my-5">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                        <h3 class="fw-bold text-white mb-0 d-flex align-items-center">
                            <span class="bg-danger rounded-3 p-2 me-2 d-inline-flex"><i class="fa-solid fa-book fs-5 text-white"></i></span>
                            '.$categoryName.'
                        </h3>
                        <a href="index.php?id_category='.$categoryId.'" class="btn btn-sm btn-outline-danger rounded-pill">Xem tất cả <i class="fa-solid fa-chevron-right ms-1"></i></a>
                    </div>
                    <div class="row g-4">';

                while ($r1 = $q1->fetch_array()) {
                    $cartLink = !isset($_SESSION['user']) ? 'login.php' : 'index.php?id_product='.$r1['id'];

                    $s .= '
                    <div class="col-6 col-md-4 col-lg-3 d-flex">
                        <div class="card manga-card w-100 d-flex flex-column">
                            <div class="manga-img-container">
                                <span class="badge-hot"><i class="fa-solid fa-star me-1"></i>Hot</span>
                                <img src="accets/images/'.$r1['image'].'" class="card-img-top" style="aspect-ratio: 3/4; object-fit: cover;" alt="'.$r1['name'].'" />
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title fw-bold text-white mb-2 text-truncate">'.$r1['name'].'</h6>
                                <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
                                    <span class="text-danger fw-bold fs-5">'.number_format($r1['price']).' đ</span>
                                    <span class="text-secondary small"><i class="fa-solid fa-eye me-1"></i>1.2k</span>
                                </div>
                                <div class="mt-3">
                                    <a href="'.$cartLink.'" class="btn btn-manga w-100 py-2 text-center d-block"><i class="fa-solid fa-cart-plus me-1"></i> Thêm Giỏ Hàng</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }

                $s .= '
                    </div>
                </section>';
            }
        }
    }
    
    $s .= '</div>';
    echo $s;
}

function cart() {
    $subtotal = 0;
    if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $x){
            $subtotal += $x->price * $x->quantity;
        }
    }
    $shipping = ($subtotal > 0) ? 20000 : 0;
    $total = $subtotal + $shipping;
    $itemCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

    $s = '
    <section class="container my-5">
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="card manga-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-white mb-0"><i class="fa-solid fa-basket-shopping text-danger me-2"></i>Giỏ Hàng Của Bạn</h4>
                <a href="cart.php?clear=OK" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can me-1"></i>Xóa Trống Giỏ Hàng</a>
            </div>
            <p class="text-secondary small mb-4">Bạn đang có <strong>'.$itemCount.'</strong> loại truyện trong giỏ hàng.</p>';

    if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
        foreach($_SESSION['cart'] as $x){
            $s .= '
            <div class="card bg-dark border-secondary mb-3 p-3 text-white">
              <div class="row align-items-center">
                <div class="col-3 col-md-2">
                  <img src="accets/images/'.$x->image.'" class="img-fluid rounded" style="aspect-ratio: 3/4; object-fit: cover;">
                </div>
                <div class="col-9 col-md-6">
                  <h6 class="fw-bold mb-1">'.$x->name.'</h6>
                  <span class="text-danger small">Đơn giá: '.number_format($x->price).' đ</span>
                </div>
                <div class="col-6 col-md-2 text-center mt-3 mt-md-0">
                  <span class="badge bg-secondary px-3 py-2 fs-6">SL: '.$x->quantity.'</span>
                </div>
                <div class="col-6 col-md-2 text-end mt-3 mt-md-0">
                  <span class="fw-bold text-danger">'.number_format($x->price * $x->quantity).' đ</span>
                </div>
              </div>
            </div>';
        }
    } else {
        $s .= '<div class="text-center py-5 text-secondary">Giỏ hàng của bạn đang trống! Hãy chọn thêm truyện yêu thích nhé.</div>';
    }

    $s .= '
            <div class="mt-3">
                <a href="index.php" class="text-decoration-none text-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Tiếp tục mua sắm</a>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card manga-card p-4 bg-dark text-white border-secondary">
            <h4 class="fw-bold mb-4"><i class="fa-solid fa-credit-card text-danger me-2"></i>Thông Tin Thanh Toán</h4>
            
            <div class="d-flex justify-content-between mb-2 text-secondary">
              <span>Tạm tính</span>
              <span>'.number_format($subtotal).' đ</span>
            </div>
            <div class="d-flex justify-content-between mb-3 text-secondary border-bottom border-secondary pb-3">
              <span>Phí vận chuyển</span>
              <span>'.number_format($shipping).' đ</span>
            </div>
            <div class="d-flex justify-content-between mb-4 fs-5 fw-bold text-white">
              <span>Tổng thanh toán</span>
              <span class="text-danger">'.number_format($total).' đ</span>
            </div>

            <button type="button" class="btn btn-manga w-100 py-3 fw-bold shadow">
              <i class="fa-solid fa-shield-halved me-2"></i> Tiến Hành Thanh Toán
            </button>
          </div>
        </div>
      </div>
    </section>';
    echo $s;
}

function login() {
    if (isset($_POST['emailphone']) && isset($_POST['password'])) {
        $emailphone = trim($_POST['emailphone']);
        $password = $_POST['password'];
        
        if (is_numeric($emailphone)) {
            $q = Database::query("SELECT * FROM users WHERE phone='".$emailphone."' AND password='".$password."'");
        } else {
            $q = Database::query("SELECT * FROM users WHERE email='".$emailphone."' AND password='".$password."'");
        }

        if ($r = $q->fetch_array()) {
            if ($r['role'] == 'admin') {
                header('location: admin.php'); 
                exit();
            } else {
                $_SESSION['user'] = $r;
                header('location: index.php'); 
                exit();
            }
        } else {
            $_SESSION['login_fail'] = "Email/SĐT hoặc Mật khẩu không chính xác!";
            header('location: login.php'); 
            exit();
        }
    }

    $s = '
    <section class="py-5 my-5">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-8 col-lg-5">
            <div class="card manga-card p-4 p-md-5">
              
              <div class="text-center mb-4">
                <h2 class="fw-bold text-white"><i class="fa-solid fa-user-shield text-danger me-2"></i>Đăng Nhập</h2>
                <p class="text-secondary small">Chào mừng bạn trở lại với Otaku Realm</p>
              </div>';

              if (isset($_SESSION['login_fail'])) {
                  $s .= '<div class="alert alert-danger border-0 bg-danger text-white py-2 mb-4 text-center small">'.$_SESSION['login_fail'].'</div>';
                  unset($_SESSION['login_fail']);
              }

              $s .= '
              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label text-secondary small">Email hoặc Số điện thoại</label>
                  <input type="text" name="emailphone" class="form-control bg-dark text-white border-secondary p-3" placeholder="Nhập email hoặc SĐT..." required />
                </div>

                <div class="mb-3">
                  <label class="form-label text-secondary small">Mật khẩu</label>
                  <input type="password" name="password" class="form-control bg-dark text-white border-secondary p-3" placeholder="••••••••" required />
                </div>

                <button type="submit" class="btn btn-manga w-100 py-3 fs-6 mb-3 shadow">Đăng Nhập Ngay</button>

                <div class="text-center text-secondary small">
                  Chưa có tài khoản? <a href="register.php" class="text-danger fw-bold text-decoration-none">Đăng ký ngay</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>';
    echo $s;
}

function tachTen($hoten) {
    $arr = explode(' ', $hoten);
    return end($arr);
}

function register() {    
    $errName = $errEmail = $errPhone = $errPwd = $errRepwd = '';
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
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
            Database::query("INSERT INTO users(name, email, phone, password, role) VALUES('".$_POST['name']."', '".$_POST['email']."', '".$_POST['phone']."', '".$_POST['pwd']."', 'user')");
            $q = Database::query("SELECT * FROM users WHERE email='".$_POST['email']."' AND password='".$_POST['pwd']."'");
            $_SESSION['user'] = $q->fetch_array();
            header('location: index.php');
            exit();
        }
    }

    $s = '
    <section class="py-5 my-4">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-9 col-lg-7">
            <div class="card manga-card p-4 p-md-5">
              <div class="text-center mb-4">
                <h2 class="fw-bold text-white"><i class="fa-solid fa-user-plus text-danger me-2"></i>Đăng Ký Tài Khoản</h2>
              </div>
              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label text-secondary">Họ và Tên</label>
                  <input type="text" name="name" class="form-control bg-dark text-white border-secondary p-2" value="'.($_POST['name'] ?? '').'" />
                  <div class="text-danger small mt-1">'.$errName.'</div>
                </div>

                <div class="mb-3">
                  <label class="form-label text-secondary">Địa chỉ Email</label>
                  <input type="text" name="email" class="form-control bg-dark text-white border-secondary p-2" value="'.($_POST['email'] ?? '').'" />
                  <div class="text-danger small mt-1">'.$errEmail.'</div>
                </div>

                <div class="mb-3">
                  <label class="form-label text-secondary">Số điện thoại (10 chữ số)</label>
                  <input type="text" name="phone" class="form-control bg-dark text-white border-secondary p-2" value="'.($_POST['phone'] ?? '').'" />
                  <div class="text-danger small mt-1">'.$errPhone.'</div>
                </div>

                <div class="mb-3">
                  <label class="form-label text-secondary">Mật khẩu</label>
                  <input type="password" name="pwd" class="form-control bg-dark text-white border-secondary p-2" />
                  <div class="text-danger small mt-1">'.$errPwd.'</div>
                </div>

                <div class="mb-3">
                  <label class="form-label text-secondary">Nhập lại mật khẩu</label>
                  <input type="password" name="repwd" class="form-control bg-dark text-white border-secondary p-2" />
                  <div class="text-danger small mt-1">'.$errRepwd.'</div>
                </div>

                <button type="submit" class="btn btn-manga w-100 py-3 fs-6">Đăng Ký Ngay</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>';
    echo $s;
}
?>