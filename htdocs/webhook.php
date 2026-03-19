<?php
include 'config.php';
header('Content-Type: application/json');

// Hàm ghi log để bạn kiểm tra nếu có lỗi
function writeLog($msg) {
    file_put_contents('webhook_log.txt', date('Y-m-d H:i:s')." - $msg\n", FILE_APPEND);
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) { die("No data"); }

$amount = $data['transferAmount'] ?? 0;
$content = $data['content'] ?? "";
$trans_id = $data['referenceCode'] ?? "";

// Ghi log để biết tiền có về không
writeLog("Nhận tín hiệu: $amount - $content - $trans_id");

// LOGIC TỰ ĐỘNG CỘNG TIỀN
if ($amount > 0 && preg_match('/NAP\W*([a-zA-Z0-9_]+)/i', $content, $matches)) {
    $username = strtoupper(trim($matches[1]));
    
    // 1. Tìm User
    $check = mysqli_query($conn, "SELECT id, balance FROM users WHERE username = '$username' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $u = mysqli_fetch_assoc($check);
        $uid = $u['id'];

        // 2. Kiểm tra xem giao dịch này đã cộng chưa (Chống cộng lặp)
        $dup = mysqli_query($conn, "SELECT id FROM deposits WHERE trans_code = '$trans_id'");
        if (mysqli_num_rows($dup) == 0) {
            
            // A. CỘNG TIỀN VÀO TÀI KHOẢN KHÁCH LUÔN
            $new_bal = $u['balance'] + $amount;
            mysqli_query($conn, "UPDATE users SET balance = $new_bal WHERE id = $uid");

            // B. LƯU LỊCH SỬ LÀ "APPROVED" (Thành công ngay)
            $now = date('Y-m-d H:i:s');
            mysqli_query($conn, "INSERT INTO deposits (user_id, amount, trans_code, status, created_at) VALUES ('$uid', '$amount', '$trans_id', 'approved', '$now')");
            
            writeLog("THÀNH CÔNG: Đã cộng $amount cho $username");
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "duplicate"]);
        }
    } else {
        writeLog("LỖI: Không tìm thấy user $username");
    }
}
?>