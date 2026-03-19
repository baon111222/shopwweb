<?php
// FILE NÀY DÙNG ĐỂ NÂNG CẤP DATABASE TỰ ĐỘNG
// SAU KHI CHẠY XONG HÃY XÓA FILE NÀY ĐI

include 'config.php';
echo "<h1>Đang tiến hành nâng cấp hệ thống...</h1>";

function fix_table($conn, $sql, $msg) {
    if(mysqli_query($conn, $sql)) {
        echo "<p style='color:green'>✅ $msg thành công!</p>";
    } else {
        // Lỗi 1060 là lỗi "Cột đã tồn tại" -> Tức là đã có rồi, không sao cả
        if(mysqli_errno($conn) == 1060) {
            echo "<p style='color:blue'>ℹ️ $msg: Đã có sẵn (Bỏ qua).</p>";
        } else {
            echo "<p style='color:red'>❌ $msg thất bại: " . mysqli_error($conn) . "</p>";
        }
    }
}

// 1. Nâng cấp bảng USERS (Thêm quyền Admin, ngày tạo)
fix_table($conn, "ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'member'", "Thêm cột Quyền hạn (role)");
fix_table($conn, "ALTER TABLE users ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP", "Thêm cột Ngày tạo (created_at)");

// 2. Nâng cấp bảng PRODUCTS (Thêm trạng thái bán, người mua)
fix_table($conn, "ALTER TABLE products ADD COLUMN status VARCHAR(20) DEFAULT 'active'", "Thêm cột Trạng thái (status)");
fix_table($conn, "ALTER TABLE products ADD COLUMN buyer_id INT(11) DEFAULT NULL", "Thêm cột Người mua (buyer_id)");
fix_table($conn, "ALTER TABLE products ADD COLUMN order_code VARCHAR(50) DEFAULT NULL", "Thêm cột Mã đơn hàng (order_code)");
fix_table($conn, "ALTER TABLE products ADD COLUMN sold_at DATETIME DEFAULT NULL", "Thêm cột Thời gian bán (sold_at)");

// 3. Nâng cấp bảng DEPOSITS (Thêm lý do hủy)
fix_table($conn, "ALTER TABLE deposits ADD COLUMN status VARCHAR(20) DEFAULT 'pending'", "Thêm cột Trạng thái nạp (status)");
fix_table($conn, "ALTER TABLE deposits ADD COLUMN reject_reason TEXT DEFAULT NULL", "Thêm cột Lý do từ chối (reject_reason)");

echo "<hr><h3>🎉 ĐÃ NÂNG CẤP XONG! BẠN CÓ THỂ VÀO LẠI WEB.</h3>";
echo "<a href='index.php' style='font-size:20px; font-weight:bold'>👉 BẤM VÀO ĐÂY ĐỂ VỀ TRANG CHỦ</a>";
?>