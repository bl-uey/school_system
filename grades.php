<?php
require_once 'config.php';
requireLogin();


// منع الدخول بدون تسجيل
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// إضافة درجة (فقط admin)
if(isset($_POST['add_grade'])){

    if($_SESSION['role'] !== 'admin'){
        die("غير مسموح لك بإضافة درجات");
    }

    $stmt = $pdo->prepare("INSERT INTO grades (student_id, course_id, grade, exam_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['student_id'], $_POST['course_id'], $_POST['grade'], $_POST['exam_date']]);
    header("Location: grades.php"); 
    exit;
}

// جلب البيانات
$grades = $pdo->query("SELECT g.*, s.name AS student_name, c.name AS course_name 
FROM grades g 
JOIN students s ON g.student_id=s.id 
JOIN courses c ON g.course_id=c.id 
ORDER BY g.exam_date DESC")->fetchAll();

$students = $pdo->query("SELECT id, name FROM students")->fetchAll();
$courses = $pdo->query("SELECT id, name FROM courses")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الدرجات - نظام مدرستي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f6; }
        .sidebar { width: 260px; background: #2c3e50; color: #fff; min-height: 100vh; padding: 20px; position: fixed; right: 0; }
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
    <a href="students.php"><i class="fa fa-user-graduate"></i> الطلاب</a>
    <a href="teachers.php"><i class="fa fa-chalkboard-teacher"></i> المعلمون</a>
    <a href="courses.php"><i class="fa fa-book-open"></i> المواد</a>
    <a href="classes.php"><i class="fa fa-school"></i> الفصول</a>
    <a href="grades.php" class="active"><i class="fa fa-clipboard-list"></i> الدرجات</a>
    <hr>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>سجل الدرجات</h2>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            ➕ إضافة درجة جديدة
        </button>
        <?php endif; ?>

    </div>

    <div class="card">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>الطالب</th>
                    <th>المادة</th>
                    <th>الدرجة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($grades as $grade): ?>
                <tr>
                    <td><?= htmlspecialchars($grade['student_name']) ?></td>
                    <td><?= htmlspecialchars($grade['course_name']) ?></td>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($grade['grade']) ?></span></td>
                    <td><?= htmlspecialchars($grade['exam_date']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($_SESSION['role'] === 'admin'): ?>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">إضافة درجة جديدة</h5></div>
            <div class="modal-body">

                <select name="student_id" class="form-control mb-2" required>
                    <option value="">اختر الطالب</option>
                    <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="course_id" class="form-control mb-2" required>
                    <option value="">اختر المادة</option>
                    <?php foreach($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="number" step="0.01" name="grade" class="form-control mb-2" placeholder="الدرجة" required>
                <input type="date" name="exam_date" class="form-control mb-2" required>

            </div>
            <div class="modal-footer">
                <button type="submit" name="add_grade" class="btn btn-primary">حفظ الدرجة</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
