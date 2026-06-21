<?php
require_once 'config.php';
requireLogin();

// إضافة معلم
if(isset($_POST['add_teacher'])){
    $stmt = $pdo->prepare("INSERT INTO teachers (name, email, phone, specialization) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['specialization']]);
    header("Location: teachers.php"); exit;
}

// تعديل معلم
if(isset($_POST['edit_teacher'])){
    $stmt = $pdo->prepare("UPDATE teachers SET name=?, email=?, phone=?, specialization=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['phone'], $_POST['specialization'], $_POST['id']]);
    header("Location: teachers.php"); exit;
}

// حذف معلم
if(isset($_GET['delete'])){
    $stmt = $pdo->prepare("DELETE FROM teachers WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: teachers.php"); exit;
}

$teachers = $pdo->query("SELECT * FROM teachers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المعلمين - نظام مدرستي</title>
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
    <a href="teachers.php" class="active"><i class="fa fa-chalkboard-teacher"></i> المعلمون</a>
    <a href="courses.php"><i class="fa fa-book-open"></i> المواد</a>
    <a href="classes.php"><i class="fa fa-school"></i> الفصول</a>
    <a href="attendance.php"><i class="fa fa-calendar-check"></i> الحضور</a>
    <hr>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>قائمة المعلمين</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">➕ إضافة معلم</button>
    </div>

    <div class="card">
        <table class="table table-hover align-middle">
            <thead>
                <tr><th>الاسم</th><th>البريد</th><th>الهاتف</th><th>التخصص</th><th>الإجراءات</th></tr>
            </thead>
            <tbody>
                <?php foreach($teachers as $teacher): ?>
                <tr>
                    <td><?= htmlspecialchars($teacher['name']) ?></td>
                    <td><?= htmlspecialchars($teacher['email']) ?></td>
                    <td><?= htmlspecialchars($teacher['phone']) ?></td>
                    <td><?= htmlspecialchars($teacher['specialization']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $teacher['id'] ?>">تعديل</button>
                        <a href="?delete=<?= $teacher['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                    </td>
                </tr>

                <div class="modal fade" id="editModal<?= $teacher['id'] ?>" tabindex="-1">
                    <div class="modal-dialog"><form method="POST" class="modal-content">
                        <div class="modal-header"><h5 class="modal-title">تعديل المعلم</h5></div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $teacher['id'] ?>">
                            <input type="text" name="name" class="form-control mb-2" value="<?= $teacher['name'] ?>" required placeholder="الاسم">
                            <input type="email" name="email" class="form-control mb-2" value="<?= $teacher['email'] ?>" required placeholder="البريد">
                            <input type="text" name="phone" class="form-control mb-2" value="<?= $teacher['phone'] ?>" placeholder="الهاتف">
                            <input type="text" name="specialization" class="form-control mb-2" value="<?= $teacher['specialization'] ?>" placeholder="التخصص">
                        </div>
                        <div class="modal-footer"><button type="submit" name="edit_teacher" class="btn btn-primary">حفظ</button></div>
                    </form></div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog"><form method="POST" class="modal-content">
        <div class="modal-header"><h5 class="modal-title">إضافة معلم جديد</h5></div>
        <div class="modal-body">
            <input type="text" name="name" class="form-control mb-2" placeholder="الاسم" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="البريد" required>
            <input type="text" name="phone" class="form-control mb-2" placeholder="الهاتف">
            <input type="text" name="specialization" class="form-control mb-2" placeholder="التخصص">
        </div>
        <div class="modal-footer"><button type="submit" name="add_teacher" class="btn btn-primary">حفظ</button></div>
    </form></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>