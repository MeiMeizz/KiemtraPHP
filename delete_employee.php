<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    // Nếu chưa, điều hướng về trang đăng nhập
    header("Location: login.php");
    exit();
}

// Kiểm tra xem người dùng có quyền admin hay không
if ($_SESSION['user']['role'] !== 'admin') {
    // Nếu không có quyền, thông báo lỗi hoặc điều hướng về trang chính
    echo "Bạn không có quyền truy cập vào chức năng này.";
    exit();
}

// Kiểm tra xem có ID của nhân viên được chỉ định không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Nếu không có, điều hướng về trang chính hoặc trang quản lý nhân viên
    header("Location: index.php");
    exit();
}

// Lấy ID của nhân viên từ URL
$employee_id = $_GET['id'];

// Kết nối đến cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "QL_NhanSu");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}

// Xóa nhân viên khỏi cơ sở dữ liệu
$sql = "DELETE FROM NHANVIEN WHERE Ma_NV = '$employee_id'";

if (mysqli_query($conn, $sql)) {
    // Nếu xóa thành công, điều hướng về trang chính hoặc trang quản lý nhân viên
    header("Location: index.php");
    exit();
} else {
    // Nếu xóa không thành công, thông báo lỗi
    echo "Lỗi: " . mysqli_error($conn);
}

// Đóng kết nối
mysqli_close($conn);
?>
