<?php
require_once 'config.php';
requireLogin();



if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ADD COURSE (admin فقط)
if(isset($_POST['add_course'])){

    if($_SESSION['role'] !== 'admin'){
        die("غير مسموح لك بإضافة مواد");
    }

    $stmt = $pdo->prepare("INSERT INTO courses (name, description, teacher_id) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['description'], $_POST['teacher_id']]);
    header("Location: courses.php"); exit;
}

// جلب البيانات
$courses = $pdo->query("
SELECT c.*, t.name AS teacher_name 
FROM courses c 
LEFT JOIN teachers t ON c.teacher_id=t.id
")->fetchAll();

$teachers = $pdo->query("SELECT * FROM teachers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المواد - نظام مدرستي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f6; }
        .sidebar { width: 260px; background: #2c3e50; color: #fff; min-height: 100vh; padding: 20px; position: fixed; right: 0; }
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
    <a href="students.php"><i class="fa fa-user-graduate"></i> الطلاب</a>
    <a href="teachers.php"><i class="fa fa-chalkboard-teacher"></i> المعلمون</a>
    <a href="courses.php" class="active"><i class="fa fa-book-open"></i> المواد</a>
    <a href="classes.php"><i class="fa fa-school"></i> الفصول</a>
    <a href="attendance.php"><i class="fa fa-calendar-check"></i> الحضور</a>
    <a href="grades.php"><i class="fa fa-clipboard-list"></i> الدرجات</a>
    <hr>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>قائمة المواد الدراسية</h2>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            ➕ إضافة مادة جديدة
        </button>
        <?php endif; ?>

    </div>

    <div class="card">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>اسم المادة</th>
                    <th>الوصف</th>
                    <th>المعلم المسؤول</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($courses as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['name']) ?></td>
                    <td><?= htmlspecialchars($course['description']) ?></td>
                    <td><?= htmlspecialchars($course['teacher_name'] ?? 'غير محدد') ?></td>
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
            <div class="modal-header"><h5 class="modal-title">إضافة مادة جديدة</h5></div>
            <div class="modal-body">

                <input type="text" name="name" class="form-control mb-2" placeholder="اسم المادة" required>

                <textarea name="description" class="form-control mb-2" placeholder="وصف المادة"></textarea>

                <select name="teacher_id" class="form-control mb-2">
                    <option value="">اختر المعلم</option>
                    <?php foreach($teachers as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>"><?= $teacher['name'] ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="modal-footer">
                <button type="submit" name="add_course" class="btn btn-primary">
                    حفظ المادة
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
