<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Giáo viên</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .header {
            width: 100%;
            background-color: #0288D1;
            color: white;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .header ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: nowrap;
        }
        .header li {
            display: inline;
        }
        .header a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
            border-radius: 5px;
            white-space: nowrap;
        }
        .header a:hover {
            background-color: #0277BD;
        }
        .content {
            margin-top: 70px;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        #theme-toggle {
            position: fixed;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
        }
        footer {
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <button id="theme-toggle">
        <svg fill="#fff" width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <g id="Dark">
                <path d="M12.741,20.917a9.389,9.389,0,0,1-1.395-.105,9.141,9.141,0,0,1-1.465-17.7,1.177,1.177,0,0,1,1.21.281,1.273,1.273,0,0,1,.325,1.293,8.112,8.112,0,0,0-.353,2.68,8.266,8.266,0,0,0,4.366,6.857,7.628,7.628,0,0,0,3.711.993,1.242,1.242,0,0,1,.994,1.963h0A9.148,9.148,0,0,1,12.741,20.917ZM10.261,4.05a.211.211,0,0,0-.065.011,8.137,8.137,0,1,0,9.131,12.526h0a.224.224,0,0,0,.013-.235.232.232,0,0,0-.206-.136A8.619,8.619,0,0,1,14.946,15.1a9.274,9.274,0,0,1-4.883-7.7,9.123,9.123,0,0,1,.4-3.008.286.286,0,0,0-.069-.285A.184.184,0,0,0,10.261,4.05Z"/>
            </g>
        </svg>
    </button>
    <div class="header">
        <h2>Giáo viên</h2>
        <ul>
            <li>
                <a href="manage_exam_result.php">
                    <svg fill="#fff" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <path d="M905.92 237.76a32 32 0 0 0-52.48 36.48A416 416 0 1 1 96 512a418.56 418.56 0 0 1 297.28-398.72 32 32 0 1 0-18.24-61.44A480 480 0 1 0 992 512a477.12 477.12 0 0 0-86.08-274.24z" fill="#fff"/>
                        <path d="M630.72 113.28A413.76 413.76 0 0 1 768 185.28a32 32 0 0 0 39.68-50.24 476.8 476.8 0 0 0-160-83.2 32 32 0 0 0-18.24 61.44zM489.28 86.72a36.8 36.8 0 0 0 10.56 6.72 30.08 30.08 0 0 0 24.32 0 37.12 37.12 0 0 0 10.56-6.72A32 32 0 0 0 544 64a33.6 33.6 0 0 0-9.28-22.72A32 32 0 0 0 505.6 32a20.8 20.8 0 0 0-5.76 1.92 23.68 23.68 0 0 0-5.76 2.88l-4.8 3.84a32 32 0 0 0-6.72 10.56A32 32 0 0 0 480 64a32 32 0 0 0 2.56 12.16 37.12 37.12 0 0 0 6.72 10.56zM355.84 313.6a36.8 36.8 0 0 0-13.12 18.56l-107.52 312.96a37.44 37.44 0 0 0 2.56 35.52 32 32 0 0 0 24.96 10.56 27.84 27.84 0 0 0 17.28-5.76 43.84 43.84 0 0 0 10.56-13.44 100.16 100.16 0 0 0 7.04-15.36l4.8-12.8 17.6-49.92h118.72l24.96 69.76a45.76 45.76 0 0 0 10.88 19.2 28.8 28.8 0 0 0 20.48 8.32h2.24a27.52 27.52 0 0 0 27.84-15.68 41.28 41.28 0 0 0 0-29.44l-107.84-313.6a36.8 36.8 0 0 0-13.44-19.2 44.16 44.16 0 0 0-48 0.32z m24.32 96l41.6 125.44h-83.2zM594.88 544a66.56 66.56 0 0 0 25.6 4.16h62.4v78.72a29.12 29.12 0 0 0 32 32 26.24 26.24 0 0 0 27.2-16.32 73.28 73.28 0 0 0 4.16-26.24v-66.88h73.6a27.84 27.84 0 0 0 29.44-32 26.56 26.56 0 0 0-16-27.2 64 64 0 0 0-23.04-4.16h-64v-75.84a28.16 28.16 0 0 0-32-30.08 26.56 26.56 0 0 0-27.2 15.68 64 64 0 0 0-4.16 24v66.88h-62.72a69.44 69.44 0 0 0-25.6 4.16 26.56 26.56 0 0 0-15.68 27.2 25.92 25.92 0 0 0 16 25.92z" fill="#fff"/>
                    </svg>
                    <span class="tab-label">Quản lý Điểm số</span>
                </a>
            </li>
            <li>
                <a href="search_exam_schedule.php">
                    <svg fill="#fff" width="24px" height="24px" viewBox="0 0 24 24" id="calendar-alert-3" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color">
                        <polygon id="primary" points="5 19 5 21 3 19 5 19" style="fill: none; stroke: rgb(255, 255, 255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polygon>
                        <path id="primary-2" data-name="primary" d="M7,21H5L3,19V5A1,1,0,0,1,4,4H20a1,1,0,0,1,1,1V20a1,1,0,0,1-1,1H17" style="fill: none; stroke: rgb(255, 255, 255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                        <line id="primary-3" data-name="primary" x1="21" y1="9" x2="3" y2="9" style="fill: none; stroke: rgb(255, 255, 255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line>
                        <path id="secondary" d="M7,5V3m5,2V3m5,2V3M12,13v3" style="fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2; stroke: rgb(44, 169, 188);"></path>
                        <line id="secondary-upstroke" x1="11.95" y1="20.5" x2="12.05" y2="20.5" style="fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2; stroke: rgb(44, 169, 188);"></line>
                    </svg>
                    <span class="tab-label">Tra cứu Lịch coi thi</span>
                </a>
            </li>
            <li>
                <a href="index.php">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none">
                        <path fill="#fff" fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3H6zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span class="tab-label"></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="content">
        <h1>Chào mừng đến với Trang chủ Giáo viên</h1>
        <p>Vui lòng chọn chức năng từ menu trên cùng để bắt đầu.</p>
    </div>
    <footer>
        <p>Bản quyền thuộc về Nguyễn Văn Quốc 2025 - Trường ĐH Lâm Nghiệp Phân hiệu tỉnh Đồng Nai</p>
    </footer>
</body>
</html>