<?php
include 'config.php';
// Nếu đã đăng nhập thì đá về trang chủ
if(isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }

$error_msg = "";

if(isset($_POST['login'])) {
    // --- 1. KIỂM TRA CAPTCHA TRƯỚC ---
    $captcha_response = $_POST['g-recaptcha-response'];
    $verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret_key&response=$captcha_response";
    $verify = json_decode(file_get_contents($verify_url));

    if (!$verify->success) {
        $error_msg = "Vui lòng xác minh bạn không phải là Robot!";
    } else {
        // --- 2. NẾU CAPTCHA ĐÚNG THÌ CHECK TÀI KHOẢN ---
        $input_val = mysqli_real_escape_string($conn, $_POST['login_input']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$input_val' OR email='$input_val'";
        $check = mysqli_query($conn, $sql);

        if(mysqli_num_rows($check) > 0){
            $row = mysqli_fetch_assoc($check);
            if(password_verify($password, $row['password'])){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['balance'] = $row['balance'];
                header("Location: index.php"); exit();
            } else {
                $error_msg = "Mật khẩu không chính xác!";
            }
        } else {
            $error_msg = "Tài khoản hoặc Email không tồn tại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập Hệ Thống</title>
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            margin: 0;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .auth-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 40px 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px; width: 100px; height: 100px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%; opacity: 0.1;
        }

        .auth-icon {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 35px; color: #fff;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .form-floating > .form-control {
            border-radius: 12px; border: 2px solid #f0f0f0;
            background: #fcfcfc; height: 55px;
        }
        .form-floating > .form-control:focus {
            border-color: #764ba2; box-shadow: none; background: #fff;
        }
        .form-floating > label { color: #999; }

        .btn-login {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border: none; height: 50px; border-radius: 12px;
            font-size: 16px; letter-spacing: 1px; transition: transform 0.2s; color: white;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.4);
        }

        .password-toggle {
            position: absolute; right: 15px; top: 50%;
            transform: translateY(-50%); cursor: pointer; color: #999; z-index: 10;
        }
        
        a { text-decoration: none; transition: 0.3s; }
        a:hover { color: #764ba2 !important; }

        /* Style cho Captcha căn giữa */
        .captcha-container { display: flex; justify-content: center; margin-bottom: 20px; }
        .g-recaptcha { transform: scale(0.9); transform-origin: 0 0; }
        @media(max-width: 400px) { .g-recaptcha { transform: scale(0.85); transform-origin: center; } }
    </style>
</head>
<body>

<div class="auth-wrapper animate__animated animate__fadeInUp">
    <div class="auth-card">
        <div class="auth-icon animate__animated animate__bounceIn">
            <i class="fa-solid fa-user-astronaut"></i>
        </div>

        <h3 class="fw-bold mb-1 text-dark">Xin Chào!</h3>
        <p class="text-muted small mb-4">Đăng nhập để tiếp tục mua sắm</p>

        <form method="POST">
            <div class="form-floating mb-3 text-start">
                <input type="text" class="form-control" name="login_input" id="loginInput" placeholder="User" required>
                <label for="loginInput"><i class="fa-solid fa-envelope me-2"></i>Tài khoản hoặc Email</label>
            </div>
            
            <div class="mb-3 position-relative text-start">
                <div class="form-floating">
                    <input type="password" class="form-control" name="password" id="passInput" placeholder="Pass" required>
                    <label for="passInput"><i class="fa-solid fa-lock me-2"></i>Mật khẩu</label>
                </div>
                <i class="fa-regular fa-eye password-toggle" onclick="togglePass('passInput')"></i>
            </div>

            <div class="captcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
            </div>

            <button type="submit" name="login" class="btn btn-primary btn-login w-100 fw-bold mb-3">
                ĐĂNG NHẬP <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <a href="index.php" class="text-secondary small"><i class="fa-solid fa-house"></i> Trang chủ</a>
                <a href="register.php" class="text-primary fw-bold small">Tạo tài khoản mới?</a>
            </div>
        </form>
    </div>
    
    <div class="text-center mt-4 text-white-50 small">
        &copy; <?php echo date("Y"); ?> All rights reserved.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function togglePass(id) {
        let x = document.getElementById(id);
        let icon = document.querySelector('.password-toggle');
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            x.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    <?php if($error_msg): ?>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: '<?php echo $error_msg; ?>',
            confirmButtonColor: '#764ba2',
            confirmButtonText: 'Thử lại'
        });
    <?php endif; ?>
</script>
</body>
</html>