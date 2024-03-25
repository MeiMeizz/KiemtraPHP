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

// Truy vấn cơ sở dữ liệu để lấy thông tin của nhân viên cần chỉnh sửa
$sql = "SELECT * FROM NHANVIEN WHERE Ma_NV = '$employee_id'";
$result = mysqli_query($conn, $sql);

// Kiểm tra xem nhân viên có tồn tại không
if (mysqli_num_rows($result) == 0) {
    // Nếu không tồn tại, điều hướng về trang chính hoặc trang quản lý nhân viên
    header("Location: index.php");
    exit();
}

// Lấy thông tin của nhân viên từ kết quả truy vấn
$employee = mysqli_fetch_assoc($result);

// Xử lý khi người dùng gửi form chỉnh sửa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nhận dữ liệu từ form
    $new_name = $_POST['new_name'];
    $new_gender = $_POST['new_gender'];
    $new_birthplace = $_POST['new_birthplace'];
    $new_department = $_POST['new_department'];
    $new_salary = $_POST['new_salary'];

    // Cập nhật thông tin nhân viên vào cơ sở dữ liệu
    $update_sql = "UPDATE NHANVIEN SET Ten_NV = '$new_name', Phai = '$new_gender', Noi_Sinh = '$new_birthplace', Ma_Phong = '$new_department', Luong = '$new_salary' WHERE Ma_NV = '$employee_id'";
    if (mysqli_query($conn, $update_sql)) {
        // Nếu cập nhật thành công, điều hướng về trang chính hoặc trang chi tiết nhân viên
        header("Location: index.php");
        exit();
    } else {
        // Nếu cập nhật không thành công, thông báo lỗi
        echo "Lỗi: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);
?>

<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin nhân viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #666;
        }

        input[type="text"],
        input[type="radio"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Chỉnh sửa thông tin nhân viên</h2>
        <form method="post" action="">
            <label for="new_name">Tên Nhân viên:</label>
            <input type="text" id="new_name" name="new_name" value="<?php echo $employee['Ten_NV']; ?>">

            <label for="new_gender">Giới tính:</label>
            <input type="radio" id="new_gender" name="new_gender" value="NU" <?php if ($employee['Phai'] == 'NU') echo 'checked'; ?>> Nữ
            <input type="radio" id="new_gender" name="new_gender" value="NAM" <?php if ($employee['Phai'] == 'NAM') echo 'checked'; ?>> Nam

            <label for="new_birthplace">Nơi sinh:</label>
            <input type="text" id="new_birthplace" name="new_birthplace" value="<?php echo $employee['Noi_Sinh']; ?>">

            <label for="new_department">Phòng ban:</label>
            <select id="new_department" name="new_department">
                <option value="QT" <?php if ($employee['Ma_Phong'] == 'QT') echo 'selected'; ?>>Quản trị</option>
                <option value="TC" <?php if ($employee['Ma_Phong'] == 'TC') echo 'selected'; ?>>Tài chính</option>
                <option value="KT" <?php if ($employee['Ma_Phong'] == 'KT') echo 'selected'; ?>>Kỹ thuật</option>
            </select>

            <label for="new_salary">Lương:</label>
            <input type="text" id="new_salary" name="new_salary" value="<?php echo $employee['Luong']; ?>">

            <input type="submit" value="Lưu chỉnh sửa">
        </form>
    </div>
</body>
</html>
