<?php

// --- CẤU HÌNH HỆ THỐNG SHOP ---

// 1. Tắt báo lỗi hiển thị ra màn hình (Bảo mật)
error_reporting(0); 
ini_set('display_errors', 0);

// 2. Cài đặt múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 3. Khởi động Session (Để lưu đăng nhập)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 4. Thông tin kết nối Database (InfinityFree)
// Lưu ý: Mật khẩu $pass là mật khẩu vPanel (hosting) của bạn
$host   = "mysql.railway.internal:3306";     // Máy chủ MySQL
$user   = "root";                // Tên đăng nhập MySQL
$pass   = "LphncSpuDavnTOYUaoiRkgMqZIzvwIXS";                 // Mật khẩu Hosting (Bạn đã điền sẵn)
$dbname = "railway";    // Tên Database
// 5. Kết nối Database
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Kiểm tra kết nối
if (!$conn) {
    // Ghi log lỗi vào file error_log (nếu cần debug thì xem file này)
    error_log("Connection failed: " . mysqli_connect_error());
    die('<div style="color:red; font-weight:bold; text-align:center; padding:50px; font-family: sans-serif;">
            <h3>❌ LỖI KẾT NỐI MÁY CHỦ!</h3>
            <p>Hệ thống đang bảo trì hoặc sai thông tin cấu hình.</p>
         </div>');
}

// 6. Ép bảng mã UTF-8 (Quan trọng: Sửa lỗi font tiếng Việt)
mysqli_set_charset($conn, 'utf8mb4');
mysqli_query($conn, "SET NAMES 'utf8mb4'");
mysqli_query($conn, "SET CHARACTER SET utf8mb4");
mysqli_query($conn, "SET SESSION collation_connection = 'utf8mb4_unicode_ci'");


// --- CÁU HÌNH GOOGLE RECAPTCHA (CHỐNG ROBOT) ---
// Bạn truy cập: https://www.google.com/recaptcha/admin/create để lấy key
// Chọn loại "reCAPTCHA v2" -> "Hộp kiểm (Checkbox)"

$recaptcha_site_key   = "6Lc13kEsAAAAAOKSMlo8kSiaTmCFPovMCvvo9JRD"; 
$recaptcha_secret_key = "6Lc13kEsAAAAABfvMnNJSAdoQHJkiYgLD2nb1qFH";


// --- CÁC HÀM HỖ TRỢ DÙNG CHUNG (HELPER FUNCTIONS) ---

// 1. Hàm định dạng tiền tệ (Ví dụ: 20000 -> 20.000 đ)
function formatMoney($number) {
    return number_format($number, 0, ',', '.') . ' đ';
}

// 2. Hàm chống Hack SQL Injection & XSS
function antiHack($str) {
    global $conn;
    $str = trim($str); // Xóa khoảng trắng thừa đầu đuôi
    $str = htmlspecialchars($str); // Chuyển ký tự đặc biệt thành mã HTML
    $str = mysqli_real_escape_string($conn, $str); // Chống hack SQL
    return $str;
}

// 3. Hàm kiểm tra đăng nhập (Trả về true nếu đã login)
function isLogin() {
    return isset($_SESSION['user_id']) ? true : false;
}

// 4. Hàm hiển thị Rank theo tổng tiền nạp
function getRank($total_money) {
    if ($total_money >= 10000000) { // 10 Triệu
        return '<span class="badge bg-danger border border-danger text-uppercase"><i class="fa-solid fa-crown"></i> BOSS</span>';
    } elseif ($total_money >= 5000000) { // 5 Triệu
        return '<span class="badge bg-warning text-dark border border-warning text-uppercase"><i class="fa-solid fa-gem"></i> Kim Cương</span>';
    } elseif ($total_money >= 1000000) { // 1 Triệu
        return '<span class="badge bg-primary border border-primary text-uppercase"><i class="fa-solid fa-medal"></i> Vàng</span>';
    } elseif ($total_money >= 200000) { // 200k
        return '<span class="badge bg-info text-dark border border-info text-uppercase"><i class="fa-solid fa-shield-halved"></i> Bạc</span>';
    } else {
        return '<span class="badge bg-secondary border border-secondary">Thành viên</span>';
    }
}

// 5. Hàm lấy thông tin User hiện tại (Nếu đã đăng nhập)
function getUserInfo() {
    global $conn;
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
        if (mysqli_num_rows($query) > 0) {
            return mysqli_fetch_assoc($query);
        }
    }
    return null;
}
?>
