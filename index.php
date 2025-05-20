<?php
session_start();

// Kết nối và tạo cơ sở dữ liệu SQLite
$db = new SQLite3('data.db');
$db->exec('PRAGMA foreign_keys = ON;');

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM Users WHERE username = :username AND password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        // Lưu userID và role vào session
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // Chuyển hướng theo vai trò
        if ($user['role'] === 'education_office') {
            header('Location: education_office_dashboard.php');
        } elseif ($user['role'] === 'teacher') {
            header('Location: teacher_dashboard.php');
        } elseif ($user['role'] === 'student') {
            header('Location: student_dashboard.php');
        }
        exit();
    } else {
        $error = "Tên người dùng hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Hệ thống quản lý trường học</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #CBE5AE, #94B447);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            display: flex;
            width: 900px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .info-section {
            background: #5D6E1E;
            color: white;
            width: 60%;
            padding: 30px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .info-section h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .info-section ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        .login-section {
            width: 40%;
            padding: 30px;
            box-sizing: border-box;
            text-align: center;
        }

        .login-section .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
            display: inline-block;
            fill: #5D6E1E; /* Màu SVG cùng màu với nền info-section */
        }

        .login-section h2 {
            margin-bottom: 20px;
            color: #5D6E1E; /* Màu chữ tiêu đề giống nền info-section */
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
            position: relative; /* Để định vị icon mắt */
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            width: 20px;
            height: 20px;
            fill: #5D6E1E; /* Màu icon mắt cùng với màu nền info-section */
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 15px;
        }

        .forgot-password a {
            color: #007bff;
            text-decoration: none;
            font-size: 12px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .login-button {
            margin-top: 15px;
            width: 100%;
            background-color: #5D6E1E; /* Màu nút giống nền info-section */
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .login-button:hover {
            background-color: #4a5b16; /* Màu tối hơn khi hover */
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .footer {
            text-align: center;
            padding: 10px;
            color: #666;
            font-size: 14px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="login-container">
            <div class="info-section">
                <h2>HỆ THỐNG QUẢN LÝ ĐIỂM SINH VIÊN ĐẠI HỌC - NGUYỄN VĂN QUỐC MSSV: 237480104020</h2>
                <ul>
                    <li>Ẩn hiện mật khẩu</li>
                    <li>Kiểm tra mật khẩu quyền truy cập và đưa đến trang tương ứng</li>
                    <li>Trang chủ admin và các trang quản lý</li>
                    <li>Giao diện trực quan dễ dàng cho các chức năng quản lý</li>
                    <li>Hỗ trợ xuất file ra CSV</li>
                    <li>Trang chủ giáo viên: Xem lịch coi thi, nhập điểm</li>
                    <li>Trang chủ học sinh: Xem điểm, xem lịch thi</li>
                </ul>
                <br />
                <strong>TÀI KHOẢN ĐĂNG NHẬP KIỂM THỬ:</strong><br />
                <p>
                    Quyền: <span style="color:red;">admin</span> <br />
                    Tên đăng nhập: <strong>admin</strong><br />
                    Mật khẩu: <strong>admin123</strong><br /><br />
                    Quyền: <span style="color:red;">teacher</span><br />
                    Tên đăng nhập: <strong>teacher1</strong><br />
                    Mật khẩu: <strong>teacher123</strong>
                </p>
            </div>

            <div class="login-section">
                <svg width="80px" height="80px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" class="logo">
                    <path fill="#5D6E1E" fill-rule="evenodd" d="M11 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3h-6zm1.293 6.293a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1 0 1.414l-3 3a1 1 0 0 1-1.414-1.414L13.586 13H5a1 1 0 1 1 0-2h8.586l-1.293-1.293a1 1 0 0 1 0-1.414z" clip-rule="evenodd" />
                </svg>
                <h2>ĐĂNG NHẬP</h2>
                <form method="post">
                    <?php if (isset($error)): ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" required />
                            <svg class="toggle-password" id="toggle-password" onclick="togglePassword()" fill="#5D6E1E" width="20px" height="20px" viewBox="0 -16 544 544" xmlns="http://www.w3.org/2000/svg">
                                <title>hide</title>
                                <path d="M108 60L468 420 436 452 362 378Q321 400 272 400 208 400 154 361 99 322 64 256 79 229 102 202 124 174 144 160L76 92 108 60ZM368 256Q368 216 340 188 312 160 272 160L229 117Q254 112 272 112 337 112 392 152 446 192 480 256 474 269 461 288 448 307 434 322L368 256ZM272 352Q299 352 322 338L293 309Q283 312 272 312 249 312 233 296 216 279 216 256 216 247 220 236L190 206Q176 229 176 256 176 296 204 324 232 352 272 352Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Forgot ?</a>
                    </div>
                    <button type="submit" class="login-button">Đăng nhập</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        Bản quyền © 2025 Nguyễn Văn Quốc
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var toggleIcon = document.getElementById("toggle-password");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.innerHTML = '<title>show</title><path d="M272 400Q205 400 151 361 96 322 64 256 96 190 151 151 205 112 272 112 336 112 392 153 448 193 480 256 448 319 392 360 336 400 272 400ZM272 352Q312 352 340 324 368 296 368 256 368 216 340 188 312 160 272 160 232 160 204 188 176 216 176 256 176 296 204 324 232 352 272 352ZM272 312Q249 312 233 296 216 279 216 256 216 233 233 217 249 200 272 200 295 200 312 217 328 233 328 256 328 279 312 296 295 312 272 312Z"/>';
            } else {
                passwordInput.type = "password";
                toggleIcon.innerHTML = '<title>hide</title><path d="M108 60L468 420 436 452 362 378Q321 400 272 400 208 400 154 361 99 322 64 256 79 229 102 202 124 174 144 160L76 92 108 60ZM368 256Q368 216 340 188 312 160 272 160L229 117Q254 112 272 112 337 112 392 152 446 192 480 256 474 269 461 288 448 307 434 322L368 256ZM272 352Q299 352 322 338L293 309Q283 312 272 312 249 312 233 296 216 279 216 256 216 247 220 236L190 206Q176 229 176 256 176 296 204 324 232 352 272 352Z"/>';
            }
        }
    </script>
</body>
</html>