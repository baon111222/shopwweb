<?php
include 'config.php';
// Nếu đã đăng nhập thì đá về trang chủ
if(isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }

$error_msg = ""; $success_msg = "";

if(isset($_POST['register'])) {
    $u = mysqli_real_escape_string($conn, $_POST['username']);
    $e = mysqli_real_escape_string($conn, $_POST['email']);
    $p = $_POST['password'];
    $rp = $_POST['re_password'];

    // --- 1. CHECK CAPTCHA ---
    $captcha_response = $_POST['g-recaptcha-response'];
    $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret_key&response=$captcha_response";
    $verify = json_decode(file_get_contents($verify_url));

    if (!$verify->success) {
        $error_msg = "Vui lòng xác minh bạn không phải là Robot!";
    } 
    // --- 2. VALIDATE DỮ LIỆU ---
    elseif(strlen($u) < 5) { $error_msg = "Tên đăng nhập quá ngắn (tối thiểu 5 ký tự)!"; }
    elseif(strlen($p) < 6) { $error_msg = "Mật khẩu hơi yếu (tối thiểu 6 ký tự)!"; }
    elseif($p != $rp) { $error_msg = "Hai mật khẩu không khớp nhau!"; }
    else {
        // Kiểm tra trùng username hoặc email
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$u' OR email='$e'");
        if(mysqli_num_rows($check) > 0) {
            $error_msg = "Tên đăng nhập hoặc Email này đã tồn tại!";
        } else {
            // Tạo tài khoản mới
            $pass_hash = password_hash($p, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email, balance, role) VALUES ('$u', '$pass_hash', '$e', 0, 'member')";
            
            if(mysqli_query($conn, $sql)){
                $success_msg = "Đăng ký thành công! Đang chuyển hướng...";
            } else {
                $error_msg = "Lỗi hệ thống: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Tài Khoản</title>
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <style>
        body {
            height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: 400% 400%; animation: gradientBG 15s ease infinite; margin: 0;
        }
        @keyframes gradientBG { 0% {background-position:0% 50%} 50% {background-position:100% 50%} 100% {background-position:0% 50%} }
        .auth-wrapper { width: 100%; max-width: 450px; padding: 20px; }
        .auth-card { background: rgba(255, 255, 255, 0.95); border-radius: 24px; padding: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); text-align: center; position: relative; overflow: hidden; }
        .auth-card::before { content: ''; position: absolute; top: -50px; left: -50px; width: 120px; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; opacity: 0.1; }
        
        .auth-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 35px; color: #fff; box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3); }
        
        .form-floating>.form-control { border-radius: 12px; border: 2px solid #f0f0f0; background: #fcfcfc; height: 55px; }
        .form-floating>.form-control:focus { border-color: #764ba2; box-shadow: none; background: #fff; }
        .form-floating>label { color: #999; }

        .btn-register { background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); border: none; height: 50px; border-radius: 12px; font-size: 16px; letter-spacing: 1px; transition: transform 0.2s; color: #fff; }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(118, 75, 162, 0.4); }

        .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999; z-index: 10; }
        a { text-decoration: none; transition: 0.3s; } a:hover { color: #764ba2 !important; }
        .captcha-container { display: flex; justify-content: center; margin-bottom: 20px; }
        .g-recaptcha { transform: scale(0.9); transform-origin: 0 0; }
        @media(max-width: 400px) { .g-recaptcha { transform: scale(0.85); transform-origin: center; } }
    </style>
</head>
<body>

<div class="auth-wrapper animate__animated animate__fadeInUp">
    <div class="auth-card">
        <div class="auth-icon animate__animated animate__bounceIn">
            <i class="fa-solid fa-user-plus"></i>
        </div>

        <h3 class="fw-bold mb-1 text-dark">Đăng Ký</h3>
        <p class="text-muted small mb-4">Tạo tài khoản để bắt đầu mua sắm</p>

        <form method="POST">
            <div class="form-floating mb-3 text-start">
                <input type="text" class="form-control" name="username" id="uInput" placeholder="User" required>
                <label for="uInput"><i class="fa-solid fa-user me-2"></i>Tên đăng nhập</label>
            </div>

            <div class="form-floating mb-3 text-start">
                <input type="email" class="form-control" name="email" id="eInput" placeholder="Email" required>
                <label for="eInput"><i class="fa-solid fa-envelope me-2"></i>Email (Để lấy lại mật khẩu)</label>
            </div>
            
            <div class="row g-2 mb-3">
                <div class="col-6 position-relative text-start">
                    <div class="form-floating">
                        <input type="password" class="form-control" name="password" id="p1" placeholder="Pass" required>
                        <label for="p1">Mật khẩu</label>
                    </div>
                </div>
                <div class="col-6 position-relative text-start">
                    <div class="form-floating">
                        <input type="password" class="form-control" name="re_password" id="p2" placeholder="Re-Pass" required>
                        <label for="p2">Nhập lại</label>
                    </div>
                </div>
            </div>

            <div class="captcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
            </div>

            <button type="submit" name="register" class="btn btn-primary btn-register w-100 fw-bold mb-3">
                TẠO TÀI KHOẢN
            </button>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="index.php" class="text-secondary small"><i class="fa-solid fa-house"></i> Trang chủ</a>
                <span class="small text-muted">Đã có tài khoản? <a href="login.php" class="fw-bold text-primary">Đăng nhập</a></span>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if($error_msg): ?>
        Swal.fire({ icon: 'error', title: 'Lỗi đăng ký', text: '<?php echo $error_msg; ?>', confirmButtonColor: '#764ba2' });
    <?php endif; ?>

    <?php if($success_msg): ?>
        Swal.fire({ icon: 'success', title: 'Thành công!', text: '<?php echo $success_msg; ?>', timer: 2000, showConfirmButton: false }).then(() => { window.location = 'login.php'; });
    <?php endif; ?>
</script>
</body>
</html>