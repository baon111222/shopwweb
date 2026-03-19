<?php
include 'config.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) { 
    echo json_encode(['balance' => 0, 'history' => []]); 
    exit(); 
}

$uid = $_SESSION['user_id'];

// 1. Lấy số dư mới nhất
$query = mysqli_query($conn, "SELECT balance FROM users WHERE id=$uid");
$user = mysqli_fetch_assoc($query);
$current_balance = intval($user['balance']);
$_SESSION['balance'] = $current_balance; // Cập nhật session

// 2. Lấy 5 giao dịch gần nhất (Cả thành công và đang chờ)
$history = [];
$sql_hist = "SELECT amount, status, created_at FROM deposits WHERE user_id=$uid ORDER BY id DESC LIMIT 5";
$res_hist = mysqli_query($conn, $sql_hist);

while($row = mysqli_fetch_assoc($res_hist)){
    $history[] = [
        'amount' => $row['amount'],
        'status' => $row['status'], // approved hoặc pending
        'time'   => date('H:i d/m', strtotime($row['created_at']))
    ];
}

echo json_encode([
    'balance' => $current_balance,
    'history' => $history
]);
?>