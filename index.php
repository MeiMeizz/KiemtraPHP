<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin Nhân viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        .add-icon {
            text-align: right;
            margin-bottom: 20px;
        }

        .add-icon a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
        }

        .add-icon a img {
            vertical-align: middle;
            margin-right: 5px;
            width: 16px; 
            height: 16px; 
        }

        .action-icons img {
            margin-right: 5px;
            cursor: pointer;
        }

        .action-icons img:hover {
            opacity: 0.7;
        }

        .no-data {
            text-align: center;
            color: #868e96;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>THÔNG TIN NHÂN VIÊN</h2>
    <div class="add-icon">
        <a href="add_employee.php">
            <img src="add.png" alt="Add Employee">
            Thêm nhân viên
        </a>
    </div>
    <table>
        <tr>
            <th>Mã Nhân Viên</th>
            <th>Tên Nhân Viên</th>
            <th>Giới Tính</th>
            <th>Nơi Sinh</th>
            <th>Tên Phòng</th>
            <th>Lương</th>
            <th>Thao tác</th>
        </tr>
        <?php
// Kiểm tra xem session đã được khởi tạo chưa
session_start();

// Kiểm tra xem biến $_SESSION['user'] có tồn tại và có giá trị không
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    // Kết nối đến cơ sở dữ liệu
    $conn = mysqli_connect("localhost", "root", "", "QL_NhanSu");
    // Kiểm tra kết nối
    if (!$conn) {
        die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
    }

    // Xác định trang hiện tại
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 5; // Số nhân viên trên mỗi trang
    $offset = ($page - 1) * $limit;

    // Truy vấn dữ liệu từ bảng NHANVIEN và PHONGBAN với phân trang
    $sql = "SELECT n.*, p.Ten_Phong FROM NHANVIEN n INNER JOIN PHONGBAN p ON n.Ma_Phong = p.Ma_Phong LIMIT $limit OFFSET $offset";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Hiển thị dữ liệu từ bảng
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["Ma_NV"] . "</td>";
            echo "<td>" . $row["Ten_NV"] . "</td>";
            // Hiển thị hình ảnh tương ứng với giới tính
            if ($row["Phai"] == "NU") {
                echo "<td><img src='woman.png' alt='woman' width='20'></td>";
            } else {
                echo "<td><img src='man.jpg' alt='man' width='20'></td>";
            }
            echo "<td>" . $row["Noi_Sinh"] . "</td>";
            echo "<td>" . $row["Ten_Phong"] . "</td>";
            echo "<td>" . $row["Luong"] . "</td>";
            // Thêm biểu tượng chỉ khi đăng nhập với tư cách là admin
            echo "<td class='action-icons'>";
            if ($_SESSION['user']['role'] === 'admin') {
                echo "<a href='edit_employee.php?id=" . $row["Ma_NV"] . "'><img src='edit.png' alt='Edit' width='20'></a>";
                echo "<a href='delete_employee.php?id=" . $row["Ma_NV"] . "'><img src='delete.jpg' alt='Delete' width='20'></a>";
            }
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
    }

    // Đếm tổng số nhân viên
    $sql_count = "SELECT COUNT(*) AS total FROM NHANVIEN";
    $result_count = mysqli_query($conn, $sql_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $total_records = $row_count['total'];

    // Tính số trang
    $total_pages = ceil($total_records / $limit);

    // Hiển thị phân trang
    echo "<tr><td colspan='7' style='text-align:center;'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
    echo "</td></tr>";
    // Đóng kết nối
    mysqli_close($conn);
} else {
    echo "<tr><td colspan='7'>Vui lòng đăng nhập để truy cập thông tin.</td></tr>";
}
?>

    </table>
</body>
</html>
