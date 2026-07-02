<?php
require_once 'config.php';
requireLogin();

// منع الوصول بدون تسجيل دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// إضافة طالب جديد (فقط admin)
if(isset($_POST['add_student'])){

    if($_SESSION['role'] !== 'admin'){
        die("غير مسموح لك بإضافة طلاب");
    }

    $stmt = $pdo->prepare("INSERT INTO students (name, email) VALUES (?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email']]);
    header("Location: students.php");
    exit;
}

$students = $pdo->query("SELECT * FROM students")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الطلاب - نظام مدرستي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f6; margin: 0; }
        
        .sidebar { width: 260px; background: #2c3e50; color: #fff; min-height: 100vh; padding: 20px; position: fixed; right: 0; top: 0; }
        .sidebar h4 { text-align: center; margin-bottom: 30px; font-weight: bold; color: #3498db; }
        .sidebar a { display: block; padding: 15px; color: #ecf0f1; text-decoration: none; border-radius: 12px; margin-bottom: 8px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #3498db; }
        
        .content { margin-right: 260px; padding: 40px; }
        
        .card { border: none; border-radius: 20px; padding: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .table thead { background: #2c3e50; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4><i class="fa fa-graduation-cap"></i> مدرستي</h4>
    <a href="index.php"><i class="fa fa-home"></i> الرئيسية</a>
    <a href="students.php" class="active"><i class="fa fa-user-graduate"></i> الطلاب</a>
    <a href="teachers.php"><i class="fa fa-chalkboard-teacher"></i> المعلمون</a>
    <a href="courses.php"><i class="fa fa-book-open"></i> المواد</a>
    <a href="classes.php"><i class="fa fa-school"></i> الفصول</a>
    <a href="attendance.php"><i class="fa fa-calendar-check"></i> الحضور</a>
    <hr>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>قائمة الطلاب</h2>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            ➕ إضافة طالب جديد
        </button>
        <?php endif; ?>
    </div>

    <div class="card">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الطالب</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($students as $student): ?>
                <tr>
                    <td><?= $student['id'] ?></td>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td>
                        <?php if($_SESSION['role'] === 'admin'): ?>
                        <button class="btn btn-sm btn-outline-warning">تعديل</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">إضافة طالب جديد</h5></div>
            <div class="modal-body">
                <input type="text" name="name" class="form-control mb-2" placeholder="اسم الطالب" required>
                <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" required>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_student" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
