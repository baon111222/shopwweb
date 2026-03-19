<?php
ob_start();
include 'config.php';
$msg = ""; $now = date('Y-m-d H:i:s');

// --- CẤU HÌNH ĐA NGÔN NGỮ ---
$lang_code = isset($_GET['lang']) && $_GET['lang'] == 'en' ? 'en' : 'vi';

$lang = [
    'vi' => [
        'title' => 'SHOP TÀI KHOẢN CAO CẤP',
        'login' => 'Đăng Nhập', 'register' => 'Đăng Ký', 'deposit' => 'Nạp Tiền', 'logout' => 'Đăng Xuất',
        'hello' => 'Xin chào', 'info' => 'Thông tin', 'admin' => 'Admin Panel',
        'top_deposit' => 'TOP ĐẠI GIA NẠP', 'top_buy' => 'TOP DÂN CHƠI MUA',
        'stock' => 'Còn', 'out_stock' => 'Hết hàng', 'sold' => 'Đã bán',
        'buy_now' => 'MUA NGAY', 'preorder' => 'ĐẶT TRƯỚC',
        'history_order' => 'Đơn Hàng', 'history_deposit' => 'Lịch Sử Nạp',
        'code' => 'Mã', 'product' => 'Sản Phẩm', 'price' => 'Giá', 'account' => 'Tài Khoản', 'copy' => 'Sao chép',
        'status' => 'Trạng Thái', 'time' => 'Thời gian',
        'success' => 'Thành công', 'pending' => 'Chờ duyệt', 'failed' => 'Thất bại', 'approved' => 'Đã cộng',
        'waiting_admin' => 'Đang chờ trả hàng...',
        'change_pass' => 'Đổi mật khẩu', 'old_pass' => 'Mật khẩu hiện tại', 'new_pass' => 'Mật khẩu mới', 're_pass' => 'Nhập lại', 'save' => 'Lưu thay đổi',
        'confirm_payment' => 'XÁC NHẬN THANH TOÁN', 'confirm_preorder' => 'XÁC NHẬN ĐẶT TRƯỚC',
        'original_price' => 'Giá gốc', 'discount' => 'Giảm giá', 'total' => 'Thanh toán', 'apply' => 'Áp dụng', 'enter_coupon' => 'Nhập mã giảm giá',
        'alert_success' => 'Thành công!', 'alert_error' => 'Lỗi', 'alert_fail' => 'Thất bại', 'alert_info' => 'Thông báo',
        'preorder_success' => 'Đặt trước thành công!',
        'preorder_msg' => 'Đơn hàng đã được lưu. Admin sẽ sớm gửi tài khoản cho bạn!',
        'preorder_exist' => 'Bạn đã có đơn đang chờ cho sản phẩm này. Vui lòng đợi Admin trả hàng!',
        'buy_success_html' => 'Mã đơn: <b>%s</b><br>Tài khoản:<br><div class="copy-box">%s</div>',
        'no_data' => 'Chưa có dữ liệu', 'preorder_mode' => 'CHẾ ĐỘ ĐẶT TRƯỚC', 'contact' => 'Liên hệ Admin', 'notice' => 'THÔNG BÁO',
        'pending_table_title' => 'Đơn Đang Chờ (Đặt Trước)', 'history_table_title' => 'Lịch Sử Mua Hàng',
        'not_enough_money' => 'Không đủ tiền trong tài khoản!', 'prod_not_found' => 'Sản phẩm không tồn tại!',
        'out_stock_msg' => 'Hết hàng, vui lòng chọn Đặt Trước!'
    ],
    'en' => [
        'title' => 'PREMIUM ACCOUNT SHOP',
        'login' => 'Login', 'register' => 'Register', 'deposit' => 'Deposit', 'logout' => 'Logout',
        'hello' => 'Hello', 'info' => 'Profile', 'admin' => 'Admin Panel',
        'top_deposit' => 'TOP DEPOSIT', 'top_buy' => 'TOP SPENDERS',
        'stock' => 'Stock', 'out_stock' => 'Out of Stock', 'sold' => 'Sold',
        'buy_now' => 'BUY NOW', 'preorder' => 'PRE-ORDER',
        'history_order' => 'Orders', 'history_deposit' => 'Deposits',
        'code' => 'Code', 'product' => 'Product', 'price' => 'Price', 'account' => 'Account', 'copy' => 'Copy',
        'status' => 'Status', 'time' => 'Time',
        'success' => 'Success', 'pending' => 'Pending', 'failed' => 'Failed', 'approved' => 'Approved',
        'waiting_admin' => 'Waiting for Admin...',
        'change_pass' => 'Change Password', 'old_pass' => 'Current Password', 'new_pass' => 'New Password', 're_pass' => 'Confirm Password', 'save' => 'Save Changes',
        'confirm_payment' => 'CONFIRM PAYMENT', 'confirm_preorder' => 'CONFIRM PRE-ORDER',
        'original_price' => 'Original Price', 'discount' => 'Discount', 'total' => 'Total', 'apply' => 'Apply', 'enter_coupon' => 'Enter Coupon Code',
        'alert_success' => 'Success!', 'alert_error' => 'Error', 'alert_fail' => 'Failed', 'alert_info' => 'Notice',
        'preorder_success' => 'Pre-order Successful!',
        'preorder_msg' => 'Order saved. Admin will send account shortly!',
        'preorder_exist' => 'You already have a pending order for this product. Please wait!',
        'buy_success_html' => 'Order ID: <b>%s</b><br>Account:<br><div class="copy-box">%s</div>',
        'no_data' => 'No data available', 'preorder_mode' => 'PRE-ORDER MODE', 'contact' => 'Contact Admin', 'notice' => 'NOTICE',
        'pending_table_title' => 'Pending Pre-orders', 'history_table_title' => 'Purchase History',
        'not_enough_money' => 'Insufficient balance!', 'prod_not_found' => 'Product not found!',
        'out_stock_msg' => 'Out of stock, please choose Pre-order!'
    ]
];
$L = $lang[$lang_code]; 

// --- THÔNG TIN LIÊN HỆ ADMIN ---
$ZALO_ADMIN = "0387641395"; 

// --- 1. SETTINGS & LOGIC ---
$curr_notice_query = mysqli_query($conn, "SELECT value FROM settings WHERE name='global_notice'");
$curr_notice = ($curr_notice_query && mysqli_num_rows($curr_notice_query) > 0) ? mysqli_fetch_assoc($curr_notice_query)['value'] : '';

// --- LOGIC BẢNG XẾP HẠNG ---
$top_status_query = mysqli_query($conn, "SELECT value FROM settings WHERE name='top_racing_status'");
$top_racing_status = ($top_status_query && mysqli_num_rows($top_status_query) > 0) ? mysqli_fetch_assoc($top_status_query)['value'] : 'on';

$top_nap = null; $top_mua = null;

if ($top_racing_status == 'on') {
    $top_start_query = mysqli_query($conn, "SELECT value FROM settings WHERE name='top_racing_start'");
    $top_start = ($top_start_query && mysqli_num_rows($top_start_query) > 0) ? mysqli_fetch_assoc($top_start_query)['value'] : '2023-01-01 00:00:00';

    $sql_nap = "SELECT u.username, SUM(d.amount) as total FROM deposits d JOIN users u ON d.user_id = u.id WHERE d.status='approved' AND d.created_at >= '$top_start' GROUP BY d.user_id, u.username ORDER BY total DESC LIMIT 5";
    $top_nap = mysqli_query($conn, $sql_nap);

    $sql_mua = "SELECT u.username, SUM(p.price) as total FROM products p JOIN users u ON p.buyer_id = u.id WHERE p.status='sold' AND p.sold_at >= '$top_start' GROUP BY p.buyer_id, u.username ORDER BY total DESC LIMIT 5";
    $top_mua = mysqli_query($conn, $sql_mua);
}

// --- XỬ LÝ MUA HÀNG & ĐẶT TRƯỚC ---
if (isset($_POST['confirm_buy']) && isset($_SESSION['user_id'])) {
    $prod_name = mysqli_real_escape_string($conn, $_POST['product_name_hidden']);
    $order_type = $_POST['order_type']; 
    $uid = $_SESSION['user_id']; 
    $code = $_POST['coupon_code_final']??''; 
    $order_code = 'DH'.rand(100000,999999);

    // Lấy thông tin sản phẩm mẫu (ưu tiên active, nếu ko có lấy sold giá cao nhất để tránh lấy đơn giảm giá)
    $prod_sample = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE name='$prod_name' AND status='active' LIMIT 1"));
    if(!$prod_sample) {
        $prod_sample = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE name='$prod_name' ORDER BY price DESC LIMIT 1"));
    }

    if ($prod_sample) {
        $original_price = $prod_sample['price']; // Giá gốc
        $final_price = $original_price;

        if ($code) { 
            $cp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM coupons WHERE code='$code' AND expiry_date > '$now'")); 
            if($cp) $final_price = $original_price * (100 - $cp['discount_percent']) / 100; 
        }

        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT balance FROM users WHERE id=$uid"));
        if ($user['balance'] >= $final_price) {
            
            if ($order_type == 'buy') {
                $prod_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE name='$prod_name' AND status='active' LIMIT 1"));
                
                if ($prod_stock) {
                    $pid = $prod_stock['id'];
                    mysqli_query($conn, "UPDATE users SET balance=balance-$final_price WHERE id=$uid");
                    
                    // Update: Lưu giá thực tế khách trả vào đơn hàng (chỉ dòng đó)
                    mysqli_query($conn, "UPDATE products SET status='sold', buyer_id=$uid, order_code='$order_code', sold_at='$now', price='$final_price' WHERE id=$pid");
                    
                    $_SESSION['balance'] = $user['balance'] - $final_price;
                    $succ_msg = sprintf($L['buy_success_html'], $order_code, addslashes($prod_stock['account_info']));
                    $_SESSION['flash_swal'] = "Swal.fire({title: '{$L['alert_success']}', html: '$succ_msg', icon: 'success'});";
                } else {
                    $_SESSION['flash_swal'] = "Swal.fire('{$L['out_stock']}', '{$L['out_stock_msg']}', 'warning');";
                }

            } elseif ($order_type == 'preorder') {
                $check_preorder = mysqli_query($conn, "SELECT id FROM products WHERE buyer_id=$uid AND name='$prod_name' AND account_info LIKE '%Đang chờ%'");
                
                if(mysqli_num_rows($check_preorder) > 0) {
                    $_SESSION['flash_swal'] = "Swal.fire('{$L['alert_info']}', '{$L['preorder_exist']}', 'info');";
                } else {
                    mysqli_query($conn, "UPDATE users SET balance=balance-$final_price WHERE id=$uid");
                    
                    $cat_id = $prod_sample['category_id'];
                    $desc = "Đơn đặt trước - Chờ Admin trả hàng";
                    $acc_info = "⏳ " . $L['waiting_admin']; // Nội dung chờ
                    
                    // Tạo đơn mới, lưu giá thực tế ($final_price) nhưng dùng tên gốc ($prod_name) để không sinh thẻ mới
                    $sql_insert = "INSERT INTO products (category_id, name, price, description, account_info, status, buyer_id, order_code, sold_at) 
                                   VALUES ('$cat_id', '$prod_name', '$final_price', '$desc', '$acc_info', 'sold', '$uid', '$order_code', '$now')";
                    
                    if(mysqli_query($conn, $sql_insert)){
                        $_SESSION['balance'] = $user['balance'] - $final_price;
                        $_SESSION['flash_swal'] = "Swal.fire({title: '{$L['preorder_success']}', text: '{$L['preorder_msg']}', icon: 'success'});";
                    } else {
                        $_SESSION['flash_swal'] = "Swal.fire('{$L['alert_error']}', 'Lỗi SQL', 'error');";
                    }
                }
            }

        } else { 
            $_SESSION['flash_swal'] = "Swal.fire('{$L['alert_fail']}', '{$L['not_enough_money']}', 'error');"; 
        }
    } else { 
        $_SESSION['flash_swal'] = "Swal.fire('{$L['alert_error']}', '{$L['prod_not_found']}', 'error');"; 
    }
    header("Location: index.php?lang=$lang_code"); exit();
}

if (isset($_POST['ajax_check_coupon'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']); $op = $_POST['price'];
    $cp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM coupons WHERE code='$code' AND expiry_date > '$now'"));
    if ($cp) { echo json_encode(['status'=>'success', 'message'=>"Giảm ".$cp['discount_percent']."%", 'new_price'=>$op*(100-$cp['discount_percent'])/100, 'discount_percent'=>$cp['discount_percent']]); } 
    else { echo json_encode(['status'=>'error', 'message'=>'Code Invalid']); } exit();
}

$swal_alert = isset($_SESSION['flash_swal']) ? $_SESSION['flash_swal'] : ""; unset($_SESSION['flash_swal']);

function showTopBadge($index) {
    if($index == 1) return '<span class="badge-top top-1">🥇 TOP 1</span>';
    if($index == 2) return '<span class="badge-top top-2">🥈 TOP 2</span>';
    if($index == 3) return '<span class="badge-top top-3">🥉 TOP 3</span>';
    return '<span class="badge-top top-normal">#'.$index.'</span>';
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $L['title']; ?></title>
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 40px rgba(118, 75, 162, 0.2);
        }
        body { font-family: 'Outfit', sans-serif; background-color: #f8f9fc; background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 30px 30px; color: #4a5568; padding-top: 80px; }
        .navbar { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.05); padding: 15px 0; transition: all 0.3s; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; background: var(--primary-grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: -0.5px; }
        .rank-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-soft); transition: transform 0.3s; border: none; }
        .rank-card:hover { transform: translateY(-5px); }
        .rank-header { padding: 15px; font-weight: 700; color: #fff; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        .rank-nap { background: linear-gradient(45deg, #f6d365 0%, #fda085 100%); }
        .rank-mua { background: linear-gradient(45deg, #84fab0 0%, #8fd3f4 100%); }
        .badge-top { padding: 5px 10px; border-radius: 8px; font-weight: 700; font-size: 0.8rem; }
        .top-1 { background: #fff9c4; color: #fbc02d; } .top-2 { background: #f5f5f5; color: #9e9e9e; } .top-3 { background: #ffebee; color: #d32f2f; } .top-normal { background: #f1f5f9; color: #64748b; }
        .card-product { border: none; border-radius: 20px; background: #fff; box-shadow: var(--shadow-soft); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden; position: relative; }
        .card-product:hover { transform: translateY(-8px); box-shadow: var(--shadow-hover); }
        .card-product h5 { font-weight: 700; color: #2d3748; margin-bottom: 5px; }
        .product-price { color: #764ba2; font-weight: 800; font-size: 1.2rem; background: rgba(118, 75, 162, 0.1); padding: 5px 12px; border-radius: 10px; display: inline-block; }
        .btn-primary { background: var(--primary-grad); border: none; font-weight: 600; border-radius: 12px; padding: 8px 20px; transition: 0.3s; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3); }
        .nav-pills .nav-link { border-radius: 12px; color: #718096; font-weight: 600; padding: 10px 20px; }
        .nav-pills .nav-link.active { background: var(--primary-grad); color: #fff; box-shadow: 0 5px 15px rgba(118, 75, 162, 0.3); }
        .cat-title { position: relative; font-weight: 800; color: #2d3748; font-size: 1.5rem; margin: 40px 0 20px; padding-left: 15px; }
        .cat-title::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 5px; height: 30px; background: var(--primary-grad); border-radius: 10px; }
        .float-contact .btn-float { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; text-decoration: none; box-shadow: 0 10px 20px rgba(0,0,0,0.2); transition: 0.3s; margin-bottom: 10px; }
        .float-contact .btn-float:hover { transform: scale(1.1); }
        .zalo { background: #0068ff; } .tele { background: #229ed9; }
        .float-contact { position: fixed; bottom: 20px; right: 20px; z-index: 999; }
        .modal-content { border-radius: 20px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .copy-box { background: #f1f5f9; padding: 15px; border: 1px dashed #cbd5e1; border-radius: 10px; font-family: monospace; color: #764ba2; font-weight: 600; word-break: break-all; }
        .btn-preorder { background: #f6c23e; color: #fff; border: none; font-weight: 700; border-radius: 12px; padding: 8px 15px; transition: 0.3s; }
        .btn-preorder:hover { background: #e0a800; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(246, 194, 62, 0.4); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php?lang=<?php echo $lang_code; ?>"><i class="fa-solid fa-layer-group me-2"></i><?php echo $L['title']; ?></a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item me-2">
                    <a href="?lang=vi" class="btn btn-sm <?php echo $lang_code=='vi'?'btn-primary':'btn-light'; ?> rounded-pill px-3">🇻🇳 VI</a>
                    <a href="?lang=en" class="btn btn-sm <?php echo $lang_code=='en'?'btn-primary':'btn-light'; ?> rounded-pill px-3">🇺🇸 EN</a>
                </li>

                <?php if(isset($_SESSION['user_id'])): 
                    $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=".$_SESSION['user_id'])); 
                    $u_email = !empty($u['email']) ? $u['email'] : 'Chưa cập nhật email';
                ?>
                    <li class="nav-item d-lg-none w-100">
                        <div class="bg-white p-3 rounded-4 shadow-sm border mt-2">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-3"><i class="fa-solid fa-user-astronaut fa-2x text-primary"></i></div>
                                <div><h6 class="fw-bold mb-0"><?php echo $u['username']; ?></h6><small class="text-success fw-bold"><?php echo number_format($u['balance']); ?>đ</small></div>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="deposit.php" class="btn btn-primary"><?php echo $L['deposit']; ?></a>
                                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#profileModal"><?php echo $L['info']; ?></button>
                                <?php if($u['role']=='admin'): ?><a href="admin.php" class="btn btn-dark"><?php echo $L['admin']; ?></a><?php endif; ?>
                                <a href="logout.php" class="btn btn-outline-danger"><?php echo $L['logout']; ?></a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item d-none d-lg-block me-3 text-end">
                        <div class="small text-muted"><?php echo $L['hello']; ?>, <span class="fw-bold text-dark"><?php echo $u['username']; ?></span></div>
                        <div class="fw-bold text-primary"><?php echo number_format($u['balance']); ?> VNĐ</div>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <a href="deposit.php" class="btn btn-primary btn-sm px-3 shadow-sm"><i class="fa-solid fa-wallet me-1"></i> <?php echo $L['deposit']; ?></a>
                        <button class="btn btn-light btn-sm border px-3 ms-1" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa-solid fa-user"></i></button>
                        <?php if($u['role']=='admin'): ?><a href="admin.php" class="btn btn-dark btn-sm ms-1">Admin</a><?php endif; ?>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm ms-1"><i class="fa-solid fa-right-from-bracket"></i></a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="btn btn-outline-primary px-4 fw-bold"><?php echo $L['login']; ?></a></li>
                    <li class="nav-item"><a href="register.php" class="btn btn-primary px-4 fw-bold"><?php echo $L['register']; ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 animate__animated animate__fadeIn">
    <?php if(!empty($curr_notice)): ?>
    <div class="alert bg-white border-0 shadow-sm d-flex align-items-center p-3 mb-5 rounded-4">
        <span class="badge bg-danger rounded-pill me-3 px-3 py-2"><?php echo $L['notice']; ?></span>
        <marquee class="fw-bold text-secondary mb-0"><?php echo htmlspecialchars($curr_notice); ?></marquee>
    </div>
    <?php endif; ?>

    <?php if($top_racing_status == 'on'): ?>
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="rank-card h-100">
                <div class="rank-header rank-nap"><i class="fa-solid fa-crown me-2"></i> <?php echo $L['top_deposit']; ?></div>
                <div class="p-3">
                    <table class="table table-borderless mb-0 align-middle">
                        <?php $i=1; if(mysqli_num_rows($top_nap)>0): while($row=mysqli_fetch_assoc($top_nap)): ?>
                        <tr><td width="20%"><?php echo showTopBadge($i); ?></td><td class="fw-bold text-dark"><?php echo substr($row['username'],0,3).'***'; ?></td><td class="text-end fw-bold text-primary"><?php echo number_format($row['total']); ?>đ</td></tr>
                        <?php $i++; endwhile; else: echo '<tr><td colspan="3" class="text-center text-muted py-4">'.$L['no_data'].'</td></tr>'; endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="rank-card h-100">
                <div class="rank-header rank-mua"><i class="fa-solid fa-bag-shopping me-2"></i> <?php echo $L['top_buy']; ?></div>
                <div class="p-3">
                    <table class="table table-borderless mb-0 align-middle">
                        <?php $j=1; if(mysqli_num_rows($top_mua)>0): while($row=mysqli_fetch_assoc($top_mua)): ?>
                        <tr><td width="20%"><?php echo showTopBadge($j); ?></td><td class="fw-bold text-dark"><?php echo substr($row['username'],0,3).'***'; ?></td><td class="text-end fw-bold text-success"><?php echo number_format($row['total']); ?>đ</td></tr>
                        <?php $j++; endwhile; else: echo '<tr><td colspan="3" class="text-center text-muted py-4">'.$L['no_data'].'</td></tr>'; endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php
    $sql = "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY c.name DESC, p.status ASC, p.price DESC"; 
    $result = mysqli_query($conn, $sql); 
    $grouped = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        if (strpos($row['name'], '(ĐẶT TRƯỚC)') !== false) continue;
        
        $cat = $row['cat_name'] ? $row['cat_name'] : 'DANH MỤC KHÁC'; 
        $pName = $row['name'];
        
        if (!isset($grouped[$cat][$pName])) {
            $grouped[$cat][$pName] = ['info' => $row, 'stock' => 0, 'sold' => 0];
        } elseif ($row['status'] == 'active') {
            $grouped[$cat][$pName]['info'] = $row;
        }

        if ($row['status'] == 'active') $grouped[$cat][$pName]['stock']++; 
        elseif ($row['status'] == 'sold') $grouped[$cat][$pName]['sold']++;
    }
    
    foreach ($grouped as $cat => $products): ?>
        <h3 class="cat-title"><?php echo $cat; ?></h3>
        <div class="row g-3 mb-5">
            <?php foreach ($products as $name => $data): $p = $data['info']; ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card card-product h-100 p-3">
                    <div class="card-body p-0 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="text-truncate" style="max-width: 65%;" title="<?php echo $p['name']; ?>"><?php echo $p['name']; ?></h5>
                            <?php if ($data['stock'] > 0): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><?php echo $L['stock']; ?> <?php echo $data['stock']; ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3"><?php echo $L['out_stock']; ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small mb-4" style="min-height: 40px;"><?php echo nl2br($p['description']); ?></p>
                        <div class="mt-auto d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <span class="product-price"><?php echo number_format($p['price']); ?>đ</span>
                                <div class="small text-muted mt-1"><i class="fa-solid fa-fire text-warning"></i> <?php echo $L['sold']; ?>: <?php echo $data['sold']; ?></div>
                            </div>
                            <div class="d-flex flex-column gap-2 text-end">
                                <?php if ($data['stock'] > 0): ?>
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <button class="btn btn-primary px-3 shadow-sm btn-sm" onclick="openBuyModal('<?php echo htmlspecialchars($p['name']); ?>', <?php echo $p['price']; ?>, 'buy')"><?php echo $L['buy_now']; ?></button>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-outline-secondary btn-sm px-3">LOGIN</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <button class="btn btn-preorder px-3 shadow-sm btn-sm" onclick="openBuyModal('<?php echo htmlspecialchars($p['name']); ?>', <?php echo $p['price']; ?>, 'preorder')"><?php echo $L['preorder']; ?></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="card border-0 shadow-sm mb-5 rounded-4 overflow-hidden">
        <div class="card-header bg-white p-3 border-bottom">
            <ul class="nav nav-pills" id="historyTab" role="tablist">
                <li class="nav-item"><button class="nav-link active" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders"><i class="fa-solid fa-bag-shopping me-2"></i><?php echo $L['history_order']; ?></button></li>
                <li class="nav-item ms-2"><button class="nav-link" id="deposits-tab" data-bs-toggle="tab" data-bs-target="#deposits"><i class="fa-solid fa-wallet me-2"></i><?php echo $L['history_deposit']; ?></button></li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="orders">
                    
                    <div class="bg-warning bg-opacity-10 p-3">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-hourglass-half"></i> <?php echo $L['pending_table_title']; ?></h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead><tr><th><?php echo $L['code']; ?></th><th><?php echo $L['product']; ?></th><th><?php echo $L['price']; ?></th><th><?php echo $L['status']; ?></th><th><?php echo $L['time']; ?></th></tr></thead>
                                <tbody>
                                <?php 
                                $wait_orders = mysqli_query($conn, "SELECT * FROM products WHERE buyer_id=".$_SESSION['user_id']." AND account_info LIKE '%Đang chờ%' ORDER BY id DESC");
                                if(mysqli_num_rows($wait_orders)>0): while($o=mysqli_fetch_assoc($wait_orders)): ?>
                                <tr>
                                    <td><span class="badge bg-warning text-dark border border-warning">#<?php echo $o['order_code']; ?></span></td>
                                    <td class="fw-bold"><?php echo $o['name']; ?></td>
                                    <td class="fw-bold text-danger"><?php echo number_format($o['price']); ?>đ</td>
                                    <td><span class="badge bg-warning text-dark"><i class="fa-regular fa-clock"></i> <?php echo $L['waiting_admin']; ?></span></td>
                                    <td><?php echo date('H:i d/m', strtotime($o['sold_at'])); ?></td>
                                </tr>
                                <?php endwhile; else: echo '<tr><td colspan="5" class="text-center small text-muted">'.$L['no_data'].'</td></tr>'; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-3">
                        <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-check-circle"></i> <?php echo $L['history_table_title']; ?></h6>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light"><tr><th><?php echo $L['code']; ?></th><th><?php echo $L['product']; ?></th><th><?php echo $L['price']; ?></th><th><?php echo $L['account']; ?></th><th><?php echo $L['copy']; ?></th><th><?php echo $L['time']; ?></th></tr></thead>
                                <tbody>
                                <?php 
                                $done_orders = mysqli_query($conn, "SELECT * FROM products WHERE buyer_id=".$_SESSION['user_id']." AND account_info NOT LIKE '%Đang chờ%' ORDER BY id DESC LIMIT 10");
                                if(mysqli_num_rows($done_orders)>0): while($o=mysqli_fetch_assoc($done_orders)): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark border">#<?php echo $o['order_code']; ?></span></td>
                                    <td class="fw-bold"><?php echo $o['name']; ?></td>
                                    <td class="fw-bold text-primary"><?php echo number_format($o['price']); ?>đ</td>
                                    <td><input type="text" class="form-control form-control-sm bg-white" value="<?php echo htmlspecialchars($o['account_info']); ?>" id="acc-<?php echo $o['id']; ?>" readonly></td>
                                    <td><button onclick="copyToClipboard('acc-<?php echo $o['id']; ?>')" class="btn btn-outline-primary btn-sm rounded-circle"><i class="fa-regular fa-copy"></i></button></td>
                                    <td><small class="text-muted"><?php echo date('H:i d/m', strtotime($o['sold_at'])); ?></small></td>
                                </tr>
                                <?php endwhile; else: echo '<tr><td colspan="6" class="text-center py-5 text-muted">'.$L['no_data'].'</td></tr>'; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="deposits">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle text-center">
                            <thead class="bg-light"><tr><th><?php echo $L['code']; ?></th><th><?php echo $L['price']; ?></th><th><?php echo $L['status']; ?></th><th><?php echo $L['time']; ?></th></tr></thead>
                            <tbody>
                            <?php $ds = mysqli_query($conn, "SELECT * FROM deposits WHERE user_id=".$_SESSION['user_id']." ORDER BY id DESC LIMIT 10");
                            if(mysqli_num_rows($ds)>0): while($d=mysqli_fetch_assoc($ds)): ?>
                            <tr>
                                <td class="font-monospace text-muted">#<?php echo $d['trans_code']; ?></td>
                                <td class="text-success fw-bold">+<?php echo number_format($d['amount']); ?>đ</td>
                                <td><?php if($d['status']=='approved') echo '<span class="badge bg-success bg-opacity-10 text-success">'.$L['approved'].'</span>'; elseif($d['status']=='pending') echo '<span class="badge bg-warning bg-opacity-10 text-warning">'.$L['pending'].'</span>'; else echo '<span class="badge bg-danger bg-opacity-10 text-danger">'.$L['failed'].'</span>'; ?></td>
                                <td class="small text-muted"><?php echo date('H:i d/m', strtotime($d['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; else: echo '<tr><td colspan="4" class="text-center py-5 text-muted">'.$L['no_data'].'</td></tr>'; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<div class="float-contact animate__animated animate__bounceInRight">
    <a href="https://zalo.me/<?php echo $ZALO_ADMIN; ?>" target="_blank" class="btn-float zalo"><i class="fa-solid fa-z"></i></a>
    <a href="https://t.me/baonguyen0333" target="_blank" class="btn-float tele"><i class="fa-brands fa-telegram"></i></a>
</div>

<div class="modal fade" id="buyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2 text-primary"><i class="fa-solid fa-cart-shopping fa-xl"></i></div>
                    <h5 class="fw-bold mt-2" id="mName"></h5>
                    <div id="preOrderBadge" class="badge bg-warning text-dark mt-1" style="display:none"><?php echo $L['preorder_mode']; ?></div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="cpInp" placeholder="<?php echo $L['enter_coupon']; ?>">
                    <button class="btn btn-dark" onclick="applyCoupon()"><?php echo $L['apply']; ?></button>
                </div>
                <small id="cpMsg" class="d-block mb-3 fw-bold"></small>

                <div class="bg-light p-3 rounded-3 mb-4">
                    <div class="d-flex justify-content-between mb-2"><span><?php echo $L['original_price']; ?>:</span> <span id="mPrice" class="fw-bold">0đ</span></div>
                    <div class="d-flex justify-content-between mb-2 text-success"><span><?php echo $L['discount']; ?>:</span> <span id="mDisc">0%</span></div>
                    <div class="border-top pt-2 d-flex justify-content-between fs-5 fw-bold text-primary"><span><?php echo $L['total']; ?>:</span> <span id="mTotal">0đ</span></div>
                </div>

                <form method="POST">
                    <input type="hidden" name="product_name_hidden" id="fProdName">
                    <input type="hidden" name="coupon_code_final" id="fCode">
                    <input type="hidden" name="order_type" id="fOrderType" value="buy">
                    <button name="confirm_buy" id="btnConfirm" class="btn btn-primary w-100 py-3 fw-bold rounded-3"><?php echo $L['confirm_payment']; ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4 text-center">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"></button>
                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3 text-primary fw-bold fs-1 border"><?php if(isset($u)) echo strtoupper(substr($u['username'],0,1)); ?></div>
                <h4 class="fw-bold"><?php if(isset($u)) echo $u['username']; ?></h4>
                <p class="text-muted mb-4"><?php if(isset($u_email)) echo $u_email; ?></p>
                <hr>
                <h6 class="fw-bold text-start mb-3"><?php echo $L['change_pass']; ?></h6>
                <form method="POST" class="text-start">
                    <div class="mb-2"><input type="password" name="old_pass" class="form-control" placeholder="<?php echo $L['old_pass']; ?>" required></div>
                    <div class="mb-2"><input type="password" name="new_pass" class="form-control" placeholder="<?php echo $L['new_pass']; ?>" required></div>
                    <div class="mb-3"><input type="password" name="re_pass" class="form-control" placeholder="<?php echo $L['re_pass']; ?>" required></div>
                    <button name="change_pass" class="btn btn-dark w-100 fw-bold"><?php echo $L['save']; ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let curP = 0; const buyModal = new bootstrap.Modal(document.getElementById('buyModal'));
    function formatMoney(n) { return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(n); }
    function openBuyModal(name, price, type) { document.getElementById('fProdName').value = name; document.getElementById('mName').innerText = name; document.getElementById('mPrice').innerText = formatMoney(price); document.getElementById('mTotal').innerText = formatMoney(price); document.getElementById('mDisc').innerText = '0%'; document.getElementById('cpInp').value = ''; document.getElementById('cpMsg').innerText = ''; document.getElementById('fOrderType').value = type; curP = price; const btn = document.getElementById('btnConfirm'); const badge = document.getElementById('preOrderBadge'); if(type === 'preorder') { btn.className = 'btn btn-warning text-white w-100 py-3 fw-bold rounded-3'; btn.innerText = '<?php echo $L['confirm_preorder']; ?>'; badge.style.display = 'inline-block'; } else { btn.className = 'btn btn-primary w-100 py-3 fw-bold rounded-3'; btn.innerText = '<?php echo $L['confirm_payment']; ?>'; badge.style.display = 'none'; } buyModal.show(); }
    function applyCoupon(){ let c = document.getElementById('cpInp').value; if(!c) return; let fd = new FormData(); fd.append('ajax_check_coupon', 1); fd.append('code', c); fd.append('price', curP); fetch('index.php', { method: 'POST', body: fd }).then(r => r.json()).then(d => { let m = document.getElementById('cpMsg'); if (d.status == 'success') { m.className = 'd-block mb-2 fw-bold text-success'; m.innerText = d.message; document.getElementById('mDisc').innerText = '-' + d.discount_percent + '%'; document.getElementById('mTotal').innerText = formatMoney(d.new_price); document.getElementById('fCode').value = c; } else { m.className = 'd-block mb-2 fw-bold text-danger'; m.innerText = d.message; } }); }
    function copyToClipboard(id) { var copyText = document.getElementById(id); copyText.select(); navigator.clipboard.writeText(copyText.value); Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '<?php echo $L['copy']; ?>!', showConfirmButton: false, timer: 1000 }); }
    <?php if($swal_alert) echo $swal_alert; ?>
</script>
</body>
</html>