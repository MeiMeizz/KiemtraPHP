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

// Kiểm tra xem người dùng đã gửi form chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra và lấy dữ liệu từ form
    $maNV = isset($_POST['maNV']) ? $_POST['maNV'] : '';
    $tenNV = isset($_POST['tenNV']) ? $_POST['tenNV'] : '';
    $phai = isset($_POST['phai']) ? $_POST['phai'] : '';
    $noiSinh = isset($_POST['noiSinh']) ? $_POST['noiSinh'] : '';
    $maPhong = isset($_POST['maPhong']) ? $_POST['maPhong'] : '';
    $luong = isset($_POST['luong']) ? $_POST['luong'] : '';

    // Kiểm tra các trường dữ liệu không được rỗng
    if (!empty($maNV) && !empty($tenNV) && !empty($phai) && !empty($noiSinh) && !empty($maPhong) && !empty($luong)) {
        // Kết nối đến cơ sở dữ liệu
        $conn = mysqli_connect("localhost", "root", "", "QL_NhanSu");

        // Kiểm tra kết nối
        if (!$conn) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
        }

        // Chuẩn bị truy vấn để thêm nhân viên
        $sql = "INSERT INTO NHANVIEN (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES ('$maNV', '$tenNV', '$phai', '$noiSinh', '$maPhong', '$luong')";

        // Thực thi truy vấn
        if (mysqli_query($conn, $sql)) {
            // Nếu thêm thành công, điều hướng về trang chính hoặc trang quản lý nhân viên
            header("Location: index.php");
            exit();
        } else {
            // Nếu có lỗi, thông báo lỗi
            echo "Lỗi: " . mysqli_error($conn);
        }

        // Đóng kết nối
        mysqli_close($conn);
    } else {
        echo "Vui lòng điền đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thêm Nhân viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm Nhân viên</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="maNV">Mã Nhân viên:</label>
            <input type="text" id="maNV" name="maNV" required><br>
            <label for="tenNV">Tên Nhân viên:</label>
            <input type="text" id="tenNV" name="tenNV" required><br>
            <label for="phai">Giới tính:</label>
            <input type="text" id="phai" name="phai" required><br>
            <label for="noiSinh">Nơi sinh:</label>
            <input type="text" id="noiSinh" name="noiSinh" required><br>
            <label for="maPhong">Mã Phòng:</label>
            <input type="text" id="maPhong" name="maPhong" required><br>
            <label for="luong">Lương:</label>
            <input type="number" id="luong" name="luong" required><br>
            <input type="submit" value="Thêm Nhân viên">
        </form>
    </div>
</body>
</html>
