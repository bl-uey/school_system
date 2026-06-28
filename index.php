<?php
require_once 'config.php';
requireLogin();

// جلب من قاعدة البيانات
$studentCount = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$teacherCount = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
$courseCount = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$classCount = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - نظام مدرستي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f6; }
        
        /* القائمة الجانبية */
        .sidebar { 
            width: 260px; background: #2c3e50; color: #fff; 
            min-height: 100vh; padding: 20px; position: fixed; 
        }
        .sidebar h4 { text-align: center; margin-bottom: 30px; font-weight: bold; color: #3498db; }
        .sidebar a { 
            display: block; padding: 15px; color: #ecf0f1; 
            text-decoration: none; border-radius: 12px; margin-bottom: 8px; transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active { background: #3498db; }
        
        /* المحتوى الرئيسي */
        .content { margin-right: 260px; padding: 40px; }
        
        /* البطاقات */
        .card { 
            border: none; border-radius: 20px; padding: 25px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); text-align: center;
            transition: 0.3s; cursor: pointer;
        }
        .card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .icon-box { font-size: 2.5rem; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h4><i class="fa fa-graduation-cap"></i> مدرستي</h4>
    <a href="index.php" class="active"><i class="fa fa-home"></i> الرئيسية</a>
    <a href="students.php"><i class="fa fa-user-graduate"></i> الطلاب</a>
    <a href="teachers.php"><i class="fa fa-chalkboard-teacher"></i> المعلمون</a>
    <a href="courses.php"><i class="fa fa-book-open"></i> المواد</a>
    <a href="classes.php"><i class="fa fa-school"></i> الفصول</a>
    <a href="grades.php"><i class="fa fa-clipboard-list"></i> الدرجات</a>
    <a href="attendance.php"><i class="fa fa-calendar-check"></i> الحضور</a>
    <hr>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">
    <div class="mb-5">
        <h2>أهلاً بكِ، مدير النظام </h2>
        <p class="text-muted">مرحباً بكِ.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card bg-white">
                <div class="icon-box text-primary"><i class="fa fa-user-graduate"></i></div>
                <h6>عدد الطلاب</h6>
                <h3><?= $studentCount ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white">
                <div class="icon-box text-success"><i class="fa fa-chalkboard-teacher"></i></div>
                <h6>عدد المعلمين</h6>
                <h3><?= $teacherCount ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white">
                <div class="icon-box text-warning"><i class="fa fa-book-open"></i></div>
                <h6>عدد المواد</h6>
                <h3><?= $courseCount ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white">
                <div class="icon-box text-danger"><i class="fa fa-school"></i></div>
                <h6>عدد الفصول</h6>
                <h3><?= $classCount ?></h3>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
