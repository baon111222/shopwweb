<?php
include 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

// --- CẤU HÌNH NGÂN HÀNG ---
$BANK_ID = "MB";              
$BANK_ACC = "0913713310";     
$BANK_NAME = "NGUYEN GIA BAO"; 
$CONTENT_PREFIX = "NAP";   

// --- CẤU HÌNH PAYPAL ---
// Lưu ý: Chỉ điền USERNAME, không điền cả link. Ví dụ: baon34566
$PAYPAL_ME_USER = "baon34566"; 

// --- TỶ GIÁ REAL-TIME ---
$EXCHANGE_RATE = 25000; 
if(function_exists('curl_init')){
    $ch = curl_init("https://open.er-api.com/v6/latest/USD");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $json = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($json, true);
    if(isset($data['rates']['VND'])) $EXCHANGE_RATE = (int)$data['rates']['VND'];
}

// --- XỬ LÝ AJAX TẠO ĐƠN PAYPAL (CHẠY NGẦM) ---
if(isset($_POST['ajax_create_pp'])) {
    header('Content-Type: application/json');
    $pp_usd = floatval($_POST['usd']);
    
    if($pp_usd >= 1) {
        $vnd_amount = $pp_usd * $EXCHANGE_RATE;
        $uid = $_SESSION['user_id'];
        $req_code = "PP_" . date("dm") . "_" . rand(1000, 9999);
        $created_at = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO deposits (user_id, amount, trans_code, status, created_at) VALUES ('$uid', '$vnd_amount', '$req_code', 'pending', '$created_at')";
        
        if(mysqli_query($conn, $sql)) {
            // Link chuẩn PayPal Me
            $final_link = "https://www.paypal.com/paypalme/$PAYPAL_ME_USER/$pp_usd";
            
            echo json_encode([
                'status' => 'success', 
                'req_code' => $req_code,
                'link' => $final_link
            ]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Lỗi hệ thống database!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Tối thiểu 1 USD!']);
    }
    exit(); 
}

// --- XỬ LÝ XÁC NHẬN PAYPAL ---
if(isset($_POST['submit_paypal'])) {
    $pp_msg = "<div class='alert alert-success fw-bold text-center mb-4'>Đã gửi yêu cầu duyệt đơn thành công!</div>";
}

// --- ĐA NGÔN NGỮ ---
$lang_code = isset($_GET['lang']) && $_GET['lang'] == 'en' ? 'en' : 'vi';

$lang = [
    'vi' => [
        'back_shop' => 'QUAY LẠI SHOP',
        'gateway_title' => 'CỔNG THANH TOÁN TỰ ĐỘNG',
        'deposit_title' => 'Nạp Tiền Vào Tài Khoản',
        'system_auto' => 'Hệ thống xử lý tự động 24/7',
        'amount_label' => 'SỐ TIỀN CẦN NẠP (VNĐ)',
        'create_qr' => 'TẠO MÃ QR',
        'min_deposit' => 'Vui lòng nạp tối thiểu 10.000đ',
        'system_error' => 'Lỗi hệ thống: Không thể tạo đơn.',
        'note_title' => 'LƯU Ý QUAN TRỌNG:',
        'note_1' => 'Vui lòng nạp tối thiểu <b>10.000đ</b>.',
        'note_2' => 'Nếu chuyển sai nội dung, vui lòng liên hệ Admin để được hỗ trợ.',
        'note_3' => 'Hệ thống duyệt tự động trong 1-3 phút.',
        'note_4' => 'Do Admin còn nghèo phần nạp tự động nó không hoạt động anh/em thông cảm nhé.',
        'note_5' => 'Cố tình spam đơn nạp ảo sẽ bị khóa tài khoản vĩnh viễn.',
        'pending_title' => 'ĐƠN HÀNG ĐANG CHỜ THANH TOÁN',
        'dont_close' => 'Vui lòng không tắt trình duyệt',
        'bank_name' => 'Ngân hàng',
        'acc_number' => 'Số tài khoản',
        'content' => 'Nội dung CK (Bắt buộc)',
        'waiting_process' => 'Đang chờ hệ thống xử lý...',
        'i_paid' => 'Tôi đã chuyển tiền',
        'recent_trans' => 'Giao dịch gần đây',
        'trans_code' => 'Mã đơn',
        'amount' => 'Số tiền',
        'status' => 'Trạng thái',
        'reason' => 'Ghi chú / Lý do',
        'time' => 'Thời gian',
        'success' => 'Thành công',
        'money_added' => 'Đã cộng tiền',
        'failed' => 'Thất bại',
        'pending' => 'Đang chờ',
        'processing' => 'Đang xử lý...',
        'system_error_note' => 'Lỗi hệ thống',
        'no_trans' => 'Chưa có giao dịch nào',
        'copied' => 'Đã sao chép!',
        'rejected_badge' => 'đơn thất bại',
        // PayPal
        'tab_bank' => 'Chuyển Khoản (VietQR)',
        'tab_paypal' => 'PayPal (Quốc Tế)',
        'pp_amount' => 'Nhập số tiền muốn nạp (USD)',
        'pp_converted' => 'Quy đổi (VNĐ)',
        'pp_trans_id' => 'Mã giao dịch PayPal (Transaction ID)',
        'pp_btn' => 'Xác Nhận Đã Chuyển',
        'pp_link_btn' => 'BẤM ĐỂ CHUYỂN TIỀN',
        'pp_note' => 'Tỉ giá cập nhật hôm nay: 1 USD = '.number_format($EXCHANGE_RATE).' VNĐ',
        'pp_success' => 'Đơn PayPal đã được gửi! Vui lòng chờ Admin duyệt.',
        'pp_error' => 'Vui lòng nhập số tiền và mã giao dịch.',
        'pp_create_link' => 'TẠO LINK THANH TOÁN',
        'pp_scan_qr' => 'QUÉT MÃ ĐỂ THANH TOÁN'
    ],
    'en' => [
        'back_shop' => 'BACK TO SHOP',
        'gateway_title' => 'AUTOMATED PAYMENT GATEWAY',
        'deposit_title' => 'Deposit Funds',
        'system_auto' => 'Automated system 24/7',
        'amount_label' => 'DEPOSIT AMOUNT (VND)',
        'create_qr' => 'CREATE QR CODE',
        'min_deposit' => 'Minimum deposit is 10,000 VND',
        'system_error' => 'System error: Cannot create order.',
        'note_title' => 'IMPORTANT NOTES:',
        'note_1' => 'Minimum deposit is <b>10,000 VND</b>.',
        'note_2' => 'If transfer content is wrong, please contact Admin.',
        'note_3' => 'System processes automatically in 1-3 minutes.',
        'note_4' => 'Auto-deposit might be slow due to budget limits, please be patient.',
        'note_5' => 'Spamming fake deposit orders will result in a permanent ban.',
        'pending_title' => 'PAYMENT PENDING',
        'dont_close' => 'Please do not close this browser',
        'bank_name' => 'Bank Name',
        'acc_number' => 'Account Number',
        'content' => 'Transfer Content (Required)',
        'waiting_process' => 'Waiting for system processing...',
        'i_paid' => 'I have transferred',
        'recent_trans' => 'Recent Transactions',
        'trans_code' => 'Code',
        'amount' => 'Amount',
        'status' => 'Status',
        'reason' => 'Note / Reason',
        'time' => 'Time',
        'success' => 'Success',
        'money_added' => 'Money added',
        'failed' => 'Failed',
        'pending' => 'Pending',
        'processing' => 'Processing...',
        'system_error_note' => 'System Error',
        'no_trans' => 'No transactions yet',
        'copied' => 'Copied!',
        'rejected_badge' => 'failed orders',
        // PayPal
        'tab_bank' => 'Bank Transfer (VietQR)',
        'tab_paypal' => 'PayPal (International)',
        'pp_amount' => 'Deposit Amount (USD)',
        'pp_converted' => 'Converted (VND)',
        'pp_trans_id' => 'PayPal Transaction ID',
        'pp_btn' => 'Confirm Payment',
        'pp_link_btn' => 'CLICK TO PAY NOW',
        'pp_note' => 'Real-time Rate: 1 USD = '.number_format($EXCHANGE_RATE).' VND',
        'pp_success' => 'PayPal order submitted! Please wait for approval.',
        'pp_error' => 'Please enter amount and transaction ID.',
        'pp_create_link' => 'CREATE PAYMENT LINK',
        'pp_scan_qr' => 'SCAN QR TO PAY'
    ]
];
$L = $lang[$lang_code];

// Nội dung chuyển khoản tự động
$user_name_clean = strtoupper(preg_replace('/\s+/', '', $_SESSION['username']));
$transfer_content = $CONTENT_PREFIX . ' ' . $user_name_clean;

$show_qr = false;
$amount_input = 0;
$req_code = "";

// XỬ LÝ TẠO ĐƠN BANK
if(isset($_POST['create_qr'])) {
    $amount_input = intval($_POST['amount']);
    if($amount_input >= 10000) {
        $uid = $_SESSION['user_id'];
        $req_code = "REQ_" . date("dm") . "_" . rand(1000, 9999);
        $created_at = date('Y-m-d H:i:s');
        $sql = "INSERT INTO deposits (user_id, amount, trans_code, status, created_at) VALUES ('$uid', '$amount_input', '$req_code', 'pending', '$created_at')";
        if(mysqli_query($conn, $sql)) { $show_qr = true; } else { $err = $L['system_error']; }
    } else { $err = $L['min_deposit']; }
}

$count_rejected = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM deposits WHERE user_id=".$_SESSION['user_id']." AND status='rejected' AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)"));
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $L['deposit_title']; ?></title>
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --shadow-soft: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        body { font-family: 'Outfit', sans-serif; background-color: #f8f9fc; background-image: radial-gradient(#e2e8f0 1px, transparent 1px); background-size: 30px 30px; color: #4a5568; }
        .card-custom { border: none; border-radius: 24px; background: #fff; box-shadow: var(--shadow-soft); overflow: hidden; }
        .qr-frame { border: 2px dashed #764ba2; padding: 10px; border-radius: 16px; background: #fdfaff; display: inline-block; transition: 0.3s; }
        .qr-frame:hover { transform: scale(1.02); border-color: #0070ba; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 12px; border: 1px dashed #cbd5e1; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .copy-btn { background: rgba(118, 75, 162, 0.1); color: #764ba2; border: none; padding: 5px 12px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; transition: 0.2s; }
        .copy-btn:hover { background: #764ba2; color: #fff; }
        .btn-primary-grad { background: var(--primary-grad); border: none; color: white; font-weight: 700; border-radius: 12px; padding: 12px; box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3); transition: 0.3s; }
        .btn-primary-grad:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(118, 75, 162, 0.5); }
        .table-custom th { background: #f1f5f9; color: #64748b; font-weight: 700; border: none; }
        .table-custom td { vertical-align: middle; border-bottom: 1px dashed #e2e8f0; font-weight: 500; }
        .badge-pulse { animation: pulse-red 2s infinite; }
        @keyframes pulse-red { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
        /* TABS CSS MỚI */
        .nav-pills .nav-link { border-radius: 12px; color: #64748b; font-weight: 700; background: #f1f5f9; margin-right: 10px; transition: 0.3s; }
        .nav-pills .nav-link.active { background: var(--primary-grad); color: white; box-shadow: 0 5px 15px rgba(118, 75, 162, 0.3); }
        
        /* PAYPAL QR & BTN */
        .btn-pp-link { background: #0070ba; color: white; border-radius: 50px; padding: 12px 30px; font-weight: bold; font-size: 1rem; text-decoration: none; display: inline-block; margin-top: 10px; box-shadow: 0 4px 10px rgba(0, 112, 186, 0.3); transition: 0.3s; }
        .btn-pp-link:hover { background: #005ea6; color: white; transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0, 112, 186, 0.5); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top mb-4 py-3">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand fw-bold" href="index.php?lang=<?php echo $lang_code; ?>" style="background: var(--primary-grad); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><i class="fa-solid fa-chevron-left me-2 text-dark"></i> <?php echo $L['back_shop']; ?></a>
        <div class="d-flex align-items-center gap-3">
            <div><a href="?lang=vi" class="btn btn-sm <?php echo $lang_code=='vi'?'btn-primary':'btn-light'; ?> rounded-pill px-2 border">🇻🇳</a><a href="?lang=en" class="btn btn-sm <?php echo $lang_code=='en'?'btn-primary':'btn-light'; ?> rounded-pill px-2 border">🇺🇸</a></div>
            <span class="navbar-text fw-bold text-dark d-none d-md-block border-start ps-3"><?php echo $L['gateway_title']; ?></span>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            
            <div class="card card-custom animate__animated animate__fadeInUp mb-4">
                <div class="card-body p-4 p-md-5">
                    
                    <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item"><button class="nav-link active" id="pills-bank-tab" data-bs-toggle="pill" data-bs-target="#pills-bank" type="button"><i class="fa-solid fa-building-columns me-2"></i> <?php echo $L['tab_bank']; ?></button></li>
                        <li class="nav-item"><button class="nav-link" id="pills-paypal-tab" data-bs-toggle="pill" data-bs-target="#pills-paypal" type="button"><i class="fa-brands fa-paypal me-2"></i> <?php echo $L['tab_paypal']; ?></button></li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        
                        <div class="tab-pane fade show active" id="pills-bank">
                            <?php if(!$show_qr): ?>
                                <div class="text-center mb-4"><div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3"><i class="fa-solid fa-wallet fa-2x text-primary"></i></div><h4 class="fw-bold text-dark"><?php echo $L['deposit_title']; ?></h4><p class="text-muted small"><?php echo $L['system_auto']; ?></p></div>
                                <?php if(isset($err) && empty($pp_msg)): ?><div class="alert alert-danger border-0 shadow-sm rounded-3 text-center mb-4"><i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $err; ?></div><?php endif; ?>
                                <form method="POST">
                                    <div class="mb-4"><label class="fw-bold mb-2 text-secondary small text-uppercase"><?php echo $L['amount_label']; ?></label><div class="input-group input-group-lg"><input type="number" name="amount" class="form-control fw-bold text-primary" placeholder="VD: 50000" min="10000" required style="border-radius: 12px;"></div><div class="d-flex justify-content-between mt-2"><span class="badge bg-light text-secondary border cursor-pointer" onclick="setAmount(20000)">20k</span><span class="badge bg-light text-secondary border cursor-pointer" onclick="setAmount(50000)">50k</span><span class="badge bg-light text-secondary border cursor-pointer" onclick="setAmount(100000)">100k</span><span class="badge bg-light text-secondary border cursor-pointer" onclick="setAmount(500000)">500k</span></div></div>
                                    <button type="submit" name="create_qr" class="btn btn-primary-grad w-100 btn-lg mb-4"><?php echo $L['create_qr']; ?> <i class="fa-solid fa-qrcode ms-2"></i></button>
                                </form>
                                <div class="alert alert-warning bg-warning bg-opacity-10 border-0 rounded-4"><h6 class="fw-bold text-warning mb-2"><i class="fa-regular fa-lightbulb me-1"></i> <?php echo $L['note_title']; ?></h6><ul class="mb-0 small text-dark ps-3" style="line-height: 1.8;"><li><?php echo $L['note_1']; ?></li><li><?php echo $L['note_2']; ?></li><li><?php echo $L['note_3']; ?></li><li><?php echo $L['note_4']; ?></li><li class="text-danger fw-bold"><?php echo $L['note_5']; ?></li></ul></div>
                            <?php else: ?>
                                <div class="text-center mb-4"><div class="qr-frame shadow-sm"><img src="https://img.vietqr.io/image/<?php echo $BANK_ID.'-'.$BANK_ACC; ?>-compact2.png?amount=<?php echo $amount_input; ?>&addInfo=<?php echo $transfer_content; ?>" width="100%" style="max-width: 280px;" class="img-fluid rounded"></div><div class="mt-2 text-danger fw-bold fs-5 animate__animated animate__pulse animate__infinite"><?php echo number_format($amount_input); ?> VNĐ</div></div>
                                <div class="info-box"><div><small class="text-muted d-block"><?php echo $L['bank_name']; ?></small><span class="fw-bold text-dark"><?php echo $BANK_ID; ?></span></div><div class="text-end"><span class="badge bg-primary">MB BANK</span></div></div>
                                <div class="info-box"><div><small class="text-muted d-block"><?php echo $L['acc_number']; ?></small><span class="fw-bold text-dark fs-5" id="stk"><?php echo $BANK_ACC; ?></span></div><button class="copy-btn" onclick="copyTxt('stk')"><i class="fa-regular fa-copy"></i></button></div>
                                <div class="info-box" style="background: #fffbeb; border-color: #fcd34d;"><div><small class="text-muted d-block"><?php echo $L['content']; ?></small><span class="fw-bold text-danger fs-6" id="noidung"><?php echo $transfer_content; ?></span></div><button class="copy-btn" style="background: rgba(217, 119, 6, 0.1); color: #d97706;" onclick="copyTxt('noidung')"><i class="fa-regular fa-copy"></i></button></div>
                                <div class="text-center mt-4"><p class="small text-muted mb-3"><i class="fa-solid fa-spinner fa-spin"></i> <?php echo $L['waiting_process']; ?></p><a href="index.php?lang=<?php echo $lang_code; ?>" class="btn btn-outline-secondary w-100 rounded-pill"><?php echo $L['i_paid']; ?></a></div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="pills-paypal">
                            
                            <?php if(isset($pp_msg)) echo $pp_msg; ?>

                            <div id="pp_step1">
                                <div class="text-center mb-4">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" width="120" class="mb-3">
                                    <p class="small text-muted"><?php echo $L['pp_note']; ?></p>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6"><label class="small fw-bold text-muted"><?php echo $L['pp_amount']; ?></label><input type="number" id="usd_input" step="0.01" class="form-control fw-bold" placeholder="10.00" oninput="calcVND(this.value)" required></div>
                                    <div class="col-6"><label class="small fw-bold text-muted"><?php echo $L['pp_converted']; ?></label><input type="text" id="vnd_val" class="form-control bg-light fw-bold text-success" readonly value="0 VNĐ"></div>
                                </div>
                                <button type="button" onclick="createPayPalOrder()" class="btn btn-outline-primary w-100 fw-bold py-3">
                                    <?php echo $L['pp_create_link']; ?> <i class="fa-solid fa-link ms-2"></i>
                                </button>
                            </div>

                            <div id="pp_step2" style="display: none;" class="text-center animate__animated animate__zoomIn">
                                <h5 class="fw-bold text-primary mb-3">QUÉT MÃ HOẶC BẤM NÚT ĐỂ THANH TOÁN</h5>
                                
                                <div class="mb-3">
                                    <img id="pp_gen_qr" src="" style="width: 200px; border: 1px solid #ddd; padding: 5px; border-radius: 10px;">
                                    <p class="small text-muted mt-1"><?php echo $L['pp_scan_qr']; ?></p>
                                </div>

                                <a id="pp_pay_link" href="#" target="_blank" class="btn-pp-link mb-3">
                                    <i class="fa-brands fa-paypal me-2"></i> <?php echo $L['pp_click_pay']; ?>
                                </a>

                                <div class="alert alert-warning mt-3 small text-start">
                                    <i class="fa-solid fa-info-circle"></i> Vui lòng ghi chú mã đơn <b id="pp_order_code" class="text-danger"></b> khi chuyển khoản.
                                </div>

                                <form method="POST" class="mt-4">
                                    <input type="hidden" name="usd" id="usd_hidden">
                                    <div class="mb-3 text-start">
                                        <label class="small fw-bold text-muted"><?php echo $L['pp_trans_id']; ?></label>
                                        <input type="text" name="tid" class="form-control" placeholder="ABC12345XYZ..." required>
                                    </div>
                                    <button type="submit" name="submit_paypal" class="btn btn-primary-grad w-100 fw-bold py-2"><?php echo $L['pp_btn']; ?></button>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="card card-custom mt-4 mb-5">
                <div class="card-header bg-white border-0 pt-3 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0 text-secondary"><i class="fa-solid fa-clock-rotate-left me-1"></i> <?php echo $L['recent_trans']; ?></h6>
                    <?php if($count_rejected > 0): ?><span class="badge bg-danger rounded-pill badge-pulse"><?php echo $count_rejected; ?> <?php echo $L['rejected_badge']; ?></span><?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0 text-center">
                            <thead><tr><th><?php echo $L['trans_code']; ?></th><th><?php echo $L['amount']; ?></th><th><?php echo $L['status']; ?></th><th><?php echo $L['reason']; ?></th><th><?php echo $L['time']; ?></th></tr></thead>
                            <tbody>
                                <?php 
                                $uid = $_SESSION['user_id'];
                                $his = mysqli_query($conn, "SELECT * FROM deposits WHERE user_id=$uid ORDER BY id DESC LIMIT 5");
                                if(mysqli_num_rows($his) > 0): while($r=mysqli_fetch_assoc($his)){
                                    if($r['status']=='approved') { $stt = '<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">'.$L['success'].'</span>'; $note = '<span class="text-success small">'.$L['money_added'].'</span>'; } elseif($r['status']=='rejected') { $stt = '<span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">'.$L['failed'].'</span>'; $note = '<span class="text-danger fw-bold small">'.($r['reject_reason'] ? $r['reject_reason'] : $L['system_error_note']).'</span>'; } else { $stt = '<span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2">'.$L['pending'].'</span>'; $note = '<span class="text-muted small">'.$L['processing'].'</span>'; }
                                    echo "<tr><td><small class='text-muted'>{$r['trans_code']}</small></td><td class='fw-bold text-dark'>+".number_format($r['amount'])."</td><td>$stt</td><td>$note</td><td><small class='text-muted'>".date('H:i d/m', strtotime($r['created_at']))."</small></td></tr>";
                                } else: echo '<tr><td colspan="5" class="py-4 text-muted small">'.$L['no_trans'].'</td></tr>'; endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function setAmount(amount) { document.querySelector('input[name="amount"]').value = amount; }
    function copyTxt(id) { var copyText = document.getElementById(id).innerText; navigator.clipboard.writeText(copyText).then(function() { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '<?php echo $L['copied']; ?>', showConfirmButton: false, timer: 1500 }); }); }
    
    // JS TÍNH TỶ GIÁ & HIỆN LINK + QR PAYPAL KHÔNG LOAD TRANG
    function calcVND(usd) { 
        let rate = <?php echo $EXCHANGE_RATE; ?>; 
        let vnd = usd * rate; 
        document.getElementById('vnd_val').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(vnd); 
    }

    function createPayPalOrder() {
        let usd = document.getElementById('usd_input').value;
        if(usd < 1) {
            Swal.fire('Error', '<?php echo $L['pp_min']; ?>', 'error');
            return;
        }
        
        // Cập nhật đúng username từ PHP (bỏ paypal.me/ thừa đi)
        let ppUser = "baon34566"; 
        
        let link = `https://www.paypal.com/paypalme/${ppUser}/${usd}`;
        let qrApi = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(link)}`;

        let fd = new FormData();
        fd.append('ajax_create_pp', 1);
        fd.append('usd', usd);

        fetch('deposit.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if(data.status === 'success') {
                document.getElementById('pp_step1').style.display = 'none';
                document.getElementById('pp_step2').style.display = 'block';
                // Hiện QR
                document.getElementById('pp_gen_qr').src = qrApi;
                // Hiện Link
                document.getElementById('pp_pay_link').href = data.link; 
                document.getElementById('pp_amount_display').innerText = usd;
                document.getElementById('pp_order_code').innerText = data.req_code; 
                document.getElementById('usd_hidden').value = usd;
            } else {
                Swal.fire('Lỗi', data.msg, 'error');
            }
        });
    }
</script>
</body>
</html>