<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'education_office') {
    header('Location: index.php');
    exit();
}

$db = new SQLite3('data.db');

$students = $db->query("SELECT * FROM Students");
$result = null;
$studentInfo = null;
$averageScore = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = $_POST['student'];
    $type = $_POST['type'];
    $semester = isset($_POST['semester']) ? $_POST['semester'] : null;

    $studentInfo = $db->querySingle("SELECT * FROM Students WHERE studentID = $studentID", true);

    if ($type === 'semester' && $semester) {
        $query = "SELECT AVG(score) as average FROM ExamResults WHERE studentID = :studentID AND semester = :semester";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':studentID', $studentID, SQLITE3_INTEGER);
        $stmt->bindValue(':semester', $semester, SQLITE3_TEXT);
        $averageResult = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        $averageScore = round($averageResult['average'], 2);

        $query = "SELECT ExamResults.*, Subjects.subjectName 
                  FROM ExamResults 
                  JOIN Subjects ON ExamResults.subjectID = Subjects.subjectID 
                  WHERE studentID = :studentID AND semester = :semester";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':studentID', $studentID, SQLITE3_INTEGER);
        $stmt->bindValue(':semester', $semester, SQLITE3_TEXT);
        $result = $stmt->execute();
    } elseif ($type === 'course') {
        $query = "SELECT AVG(score) as average FROM ExamResults WHERE studentID = :studentID";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':studentID', $studentID, SQLITE3_INTEGER);
        $averageResult = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
        $averageScore = round($averageResult['average'], 2);

        $query = "SELECT ExamResults.*, Subjects.subjectName 
                  FROM ExamResults 
                  JOIN Subjects ON ExamResults.subjectID = Subjects.subjectID 
                  WHERE studentID = :studentID";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':studentID', $studentID, SQLITE3_INTEGER);
        $result = $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tính Điểm Trung bình</title>
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
        }
        form {
            margin-bottom: 20px;
        }
        form label {
            margin-right: 10px;
        }
        form input, form select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }
        form button {
            padding: 8px 15px;
            background-color: #0288D1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0277BD;
        }
        #semester-field {
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
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
        <h2>Phòng Giáo vụ</h2>
        <ul>
            <li>
                <a href="manage_exam_schedule.php">
                    <svg fill="#fff" width="24px" height="24px" viewBox="0 0 24 24" id="calendar-alert-3" data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color">
                        <polygon id="primary" points="5 19 5 21 3 19 5 19" style="fill: none; stroke: rgb(255, 255, 255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polygon>
                        <path id="primary-2" data-name="primary" d="M7,21H5L3,19V5A1,1,0,0,1,4,4H20a1,1,0,0,1,1,1V20a1,1,0,0,1-1,1H17" style="fill: none; stroke: rgb(255, 255, 255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                        <line id="primary-3" data-name="primary" x1="21" y1="9" x2="3" y2="9" style="fill: none; stroke: rgb(255, 255, 255); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line>
                        <path id="secondary" d="M7,5V3m5,2V3m5,2V3M12,13v3" style="fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2; stroke: rgb(44, 169, 188);"></path>
                        <line id="secondary-upstroke" x1="11.95" y1="20.5" x2="12.05" y2="20.5" style="fill: none; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2; stroke: rgb(44, 169, 188);"></line>
                    </svg>
                    <span class="tab-label">Quản lý Lịch thi</span>
                </a>
            </li>
            <li>
                <a href="manage_exam_result.php">
                    <svg fill="#fff" width="24px" height="24px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <path d="M905.92 237.76a32 32 0 0 0-52.48 36.48A416 416 0 1 1 96 512a418.56 418.56 0 0 1 297.28-398.72 32 32 0 1 0-18.24-61.44A480 480 0 1 0 992 512a477.12 477.12 0 0 0-86.08-274.24z" fill="#fff"/>
                        <path d="M630.72 113.28A413.76 413.76 0 0 1 768 185.28a32 32 0 0 0 39.68-50.24 476.8 476.8 0 0 0-160-83.2 32 32 0 0 0-18.24 61.44zM489.28 86.72a36.8 36.8 0 0 0 10.56 6.72 30.08 30.08 0 0 0 24.32 0 37.12 37.12 0 0 0 10.56-6.72A32 32 0 0 0 544 64a33.6 33.6 0 0 0-9.28-22.72A32 32 0 0 0 505.6 32a20.8 20.8 0 0 0-5.76 1.92 23.68 23.68 0 0 0-5.76 2.88l-4.8 3.84a32 32 0 0 0-6.72 10.56A32 32 0 0 0 480 64a32 32 0 0 0 2.56 12.16 37.12 37.12 0 0 0 6.72 10.56zM355.84 313.6a36.8 36.8 0 0 0-13.12 18.56l-107.52 312.96a37.44 37.44 0 0 0 2.56 35.52 32 32 0 0 0 24.96 10.56 27.84 27.84 0 0 0 17.28-5.76 43.84 43.84 0 0 0 10.56-13.44 100.16 100.16 0 0 0 7.04-15.36l4.8-12.8 17.6-49.92h118.72l24.96 69.76a45.76 45.76 0 0 0 10.88 19.2 28.8 28.8 0 0 0 20.48 8.32h2.24a27.52 27.52 0 0 0 27.84-15.68 41.28 41.28 0 0 0 0-29.44l-107.84-313.6a36.8 36.8 0 0 0-13.44-19.2 44.16 44.16 0 0 0-48 0.32z m24.32 96l41.6 125.44h-83.2zM594.88 544a66.56 66.56 0 0 0 25.6 4.16h62.4v78.72a29.12 29.12 0 0 0 32 32 26.24 26.24 0 0 0 27.2-16.32 73.28 73.28 0 0 0 4.16-26.24v-66.88h73.6a27.84 27.84 0 0 0 29.44-32 26.56 26.56 0 0 0-16-27.2 64 64 0 0 0-23.04-4.16h-64v-75.84a28.16 28.16 0 0 0-32-30.08 26.56 26.56 0 0 0-27.2 15.68 64 64 0 0 0-4.16 24v66.88h-62.72a69.44 69.44 0 0 0-25.6 4.16 26.56 26.56 0 0 0-15.68 27.2 25.92 25.92 0 0 0 16 25.92z" fill="#fff"/>
                    </svg>
                    <span class="tab-label">Quản lý Kết quả thi</span>
                </a>
            </li>
            <li>
                <a href="calculate_average.php">
                    <svg fill="#fff" width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                        <path d="M12.71,17.29a1,1,0,0,0-.16-.12.56.56,0,0,0-.17-.09.6.6,0,0,0-.19-.06.93.93,0,0,0-.57.06.9.9,0,0,0-.54.54A.84.84,0,0,0,11,18a1,1,0,0,0,.07.38,1.46,1.46,0,0,0,.22.33A1,1,0,0,0,12,19a.84.84,0,0,0,.38-.08,1.15,1.15,0,0,0,.33-.21A1,1,0,0,0,13,18a1,1,0,0,0-.08-.38A1,1,0,0,0,12.71,17.29ZM8.55,13.17a.56.56,0,0,0-.17-.09A.6.6,0,0,0,8.19,13a.86.86,0,0,0-.39,0l-.18.06-.18.09-.15.12A1.05,1.05,0,0,0,7,14a1,1,0,0,0,.29.71,1.15,1.15,0,0,0,.33.21A1,1,0,0,0,9,14a1.05,1.05,0,0,0-.29-.71Zm.16,4.12a1,1,0,0,0-.33-.21A1,1,0,0,0,7.8,17l-.18.06a.76.76,0,0,0-.18.09,1.58,1.58,0,0,0-.15.12,1,1,0,0,0-.21.33.94.94,0,0,0,0,.76,1.15,1.15,0,0,0,.21.33A1,1,0,0,0,8,19a.84.84,0,0,0,.38-.08,1.15,1.15,0,0,0,.33-.21,1.15,1.15,0,0,0,.21-.33.94.94,0,0,0,0-.76A1,1,0,0,0,8.71,17.29Zm2.91-4.21a1,1,0,0,0-.33.21A1.05,1.05,0,0,0,11,14a1,1,0,0,0,1.38.92,1.15,1.15,0,0,0,.33-.21A1,1,0,0,0,13,14a1.05,1.05,0,0,0-.29-.71A1,1,0,0,0,11.62,13.08Zm5.09,4.21a1.15,1.15,0,0,0-.33-.21,1,1,0,0,0-1.09.21,1,1,0,0,0-.21.33.94.94,0,0,0,0,.76,1.15,1.15,0,0,0,.21.33A1,1,0,0,0,16,19a.84.84,0,0,0,.38-.08,1.15,1.15,0,0,0,.33-.21,1,1,0,0,0,.21-1.09A1,1,0,0,0,16.71,17.29ZM16,5H8A1,1,0,0,0,7,6v4a1,1,0,0,0,1,1h8a1,1,0,0,0,1-1V6A1,1,0,0,0,16,5ZM15,9H9V7h6Zm3-8H6A3,3,0,0,0,3,4V20a3,3,0,0,0,3,3H18a3,3,0,0,0,3-3V4A3,3,0,0,0,18,1Zm1,19a1,1,0,0,1-1,1H6a1,1,0,0,1-1-1V4A1,1,0,0,1,6,3H18a1,1,0,0,1,1,1Zm-2.45-6.83a.56.56,0,0,0-.17-.09.6.6,0,0,0-.19-.06.86.86,0,0,0-.39,0l-.18.06-.18.09-.15.12A1.05,1.05,0,0,0,15,14a1,1,0,0,0,1.38.92,1.15,1.15,0,0,0,.33-.21A1,1,0,0,0,17,14a1.05,1.05,0,0,0-.29-.71Z"/>
                    </svg>
                    <span class="tab-label">Tính Điểm Trung bình</span>
                </a>
            </li>
            <li>
                <a href="manage_graduation_exam.php">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="24px" height="24px">
                        <g>
                            <path style="fill:#fff;" d="M294.433,172.655c0-0.451,0.151-1.051,0.3-1.651l27.94-91.632c1.653-5.258,8.412-7.812,15.322-7.812
                                s13.67,2.553,15.322,7.812l28.09,91.632c0.151,0.602,0.3,1.2,0.3,1.651c0,5.559-8.561,9.615-15.022,9.615
                                c-3.756,0-6.759-1.202-7.661-4.356l-5.107-18.777H322.22l-5.107,18.777c-0.902,3.154-3.905,4.356-7.661,4.356
                                C302.994,182.269,294.433,178.213,294.433,172.655z M348.961,141.108l-10.966-40.257l-10.966,40.257H348.961z"/>
                            <path style="fill:#fff;" d="M385.143,99.685V89.022h-10.662c-3.079,0-5.166-2.748-5.166-6.924c0-3.957,2.089-7.034,5.166-7.034
                                h10.662V64.512c0-2.638,3.077-5.275,7.034-5.275c4.177,0,6.925,2.637,6.925,5.275v10.552h10.552c2.638,0,5.277,3.077,5.277,7.034
                                c0,4.176-2.638,6.924-5.277,6.924h-10.552v10.663c0,3.077-2.748,5.165-6.925,5.165C388.22,104.851,385.143,102.763,385.143,99.685z
                                "/>
                            <path style="fill:#fff;" d="M416.725,512H95.274c-29.149,0-52.864-23.715-52.864-52.865V116.121
                                c0-15.918,6.195-30.878,17.448-42.127l56.544-56.547c9.108-9.108,21.139-15.093,33.879-16.855C152.956,0.198,155.731,0,158.53,0
                                h258.194c29.149,0,52.864,23.715,52.864,52.865v272.618c0,8.162-6.619,14.781-14.781,14.781c-8.162,0-14.781-6.619-14.781-14.781
                                V52.865c0-12.848-10.452-23.303-23.301-23.303H158.53c-1.385,0-2.73,0.095-3.998,0.284c-0.056,0.009-0.111,0.016-0.166,0.024
                                c-6.411,0.879-12.471,3.89-17.059,8.48L80.761,94.898c-5.667,5.666-8.787,13.203-8.787,21.222v343.015
                                c0,12.848,10.452,23.303,23.301,23.303h321.451c12.848,0,23.301-10.453,23.301-23.303v-48.669c0-8.162,6.619-14.781,14.781-14.781
                                c8.162,0,14.781,6.619,14.781,14.781v48.669C469.587,488.285,445.872,512,416.725,512z"/>
                        </g>
                        <path style="fill:#4CAF50;" d="M152.361,15.225v481.992H95.275c-21.032,0-38.084-17.052-38.084-38.084V116.121
                            c0-11.881,4.711-23.269,13.117-31.676l56.546-56.546C133.787,20.967,142.763,16.542,152.361,15.225z"/>
                        <g>
                            <path style="fill:#fff;" d="M152.359,512H95.274c-29.149,0-52.864-23.715-52.864-52.865V116.121
                                c0-15.918,6.196-30.878,17.448-42.127l56.544-56.547c9.127-9.126,21.183-15.115,33.951-16.864
                                c4.222-0.585,8.505,0.698,11.723,3.505c3.219,2.807,5.066,6.869,5.066,11.139v481.992C167.141,505.381,160.523,512,152.359,512z
                                M137.578,38.083c-0.092,0.089-0.182,0.179-0.272,0.269L80.761,94.898c-5.667,5.666-8.787,13.203-8.787,21.222v343.015
                                c0,12.848,10.452,23.303,23.301,23.303h42.304V38.083H137.578z"/>
                            <path style="fill:#fff;" d="M371.291,340.263H152.359c-8.162,0-14.781-6.619-14.781-14.781s6.619-14.781,14.781-14.781h218.932
                                c8.162,0,14.781,6.619,14.781,14.781S379.455,340.263,371.291,340.263z"/>
                            <path style="fill:#fff;" d="M371.291,253.784H152.359c-8.162,0-14.781-6.619-14.781-14.781c0-8.162,6.619-14.781,14.781-14.781
                                h218.932c8.162,0,14.781,6.619,14.781,14.781C386.073,247.164,379.455,253.784,371.291,253.784z"/>
                            <path style="fill:#fff;" d="M371.291,426.743H152.359c-8.162,0-14.781-6.619-14.781-14.781s6.619-14.781,14.781-14.781h218.932
                                c8.162,0,14.781,6.619,14.781,14.781S379.455,426.743,371.291,426.743z"/>
                        </g>
                    </svg>
                    <span class="tab-label">Quản lý Thi Tốt nghiệp</span>
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
        <h1>Tính Điểm Trung bình</h1>
        <form action="calculate_average.php" method="post">
            <label for="student">Mã Sinh viên:</label>
            <input list="students" name="student" placeholder="Chọn sinh viên" required>
            <datalist id="students">
                <?php 
                $students->reset();
                while ($student = $students->fetchArray(SQLITE3_ASSOC)): ?>
                    <option value="<?php echo htmlspecialchars($student['studentID']); ?>" data-label="<?php echo htmlspecialchars($student['fullName']); ?>"><?php echo htmlspecialchars($student['fullName']); ?></option>
                <?php endwhile; ?>
            </datalist>

            <label for="type">Loại tính:</label>
            <select id="type" name="type" onchange="toggleSemesterField(this.value)">
                <option value="semester">Trung bình Học kỳ</option>
                <option value="course">Trung bình Toàn khóa</option>
            </select>

            <div id="semester-field">
                <label for="semester">Học kỳ:</label>
                <input type="text" id="semester" name="semester" placeholder="VD: 2025-1">
            </div>

            <button type="submit">Tính</button>
        </form>

        <?php if ($result && $studentInfo): ?>
            <h2>Kết quả Tính Điểm Trung bình</h2>
            <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($studentInfo['fullName']); ?></p>
            <p><strong>Giới tính:</strong> <?php echo htmlspecialchars($studentInfo['gender']); ?></p>
            <p><strong>Ngày sinh:</strong> <?php echo htmlspecialchars($studentInfo['birthDate']); ?></p>
            <p><strong>Quê quán:</strong> <?php echo htmlspecialchars($studentInfo['hometown']); ?></p>
            <p><strong>Điểm Trung bình:</strong> <?php echo htmlspecialchars($averageScore); ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Môn học</th>
                        <th>Điểm</th>
                        <th>Học kỳ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['subjectName']); ?></td>
                            <td><?php echo htmlspecialchars($row['score']); ?></td>
                            <td><?php echo htmlspecialchars($row['semester']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <footer>
        <p>Bản quyền thuộc về Nguyễn Văn Quốc 2025 - Trường ĐH Lâm Nghiệp Phân hiệu tỉnh Đồng Nai</p>
    </footer>
    <script>
        function toggleSemesterField(type) {
            const semesterField = document.getElementById('semester-field');
            semesterField.style.display = type === 'semester' ? 'block' : 'none';
        }
    </script>
</body>
</html>