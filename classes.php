<?php
require_once 'config.php';
requireLogin();


// منع الدخول بدون تسجيل
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ADD CLASS (admin فقط)
if(isset($_POST['add_class'])){

    if($_SESSION['role'] !== 'admin'){
        die("غير مسموح لك بإضافة فصول");
    }

    $stmt = $pdo->prepare("INSERT INTO classes (name, teacher_id) VALUES (?, ?)");
    $stmt->execute([$_POST['name'], $_POST['teacher_id']]);
    header("Location: classes.php");
    exit;
}

// EDIT CLASS (admin فقط)
if(isset($_POST['edit_class'])){

    if($_SESSION['role'] !== 'admin'){
        die("غير مسموح لك بتعديل الفصول");
    }

    $stmt = $pdo->prepare("UPDATE classes SET name=?, teacher_id=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['teacher_id'], $_POST['id']]);
    header("Location: classes.php");
    exit;
}

// ======================
// DELETE CLASS (admin فقط)
// ======================
if(isset($_GET['delete'])){

    if($_SESSION['role'] !== 'admin'){
        die("غير مسموح لك بالحذف");
    }

    $stmt = $pdo->prepare("DELETE FROM classes WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: classes.php");
    exit;
}

// جلب البيانات
$classes = $pdo->query("
SELECT c.*, t.name AS teacher_name 
FROM classes c 
LEFT JOIN teachers t ON c.teacher_id=t.id
")->fetchAll();

$teachers = $pdo->query("SELECT * FROM teachers")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الفصول الدراسية - نظام مدرستي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        body { font-family: 'Cairo', sans-serif; background-color: #f4f7f6; }
        .sidebar { width: 260px; background: #2c3e50; color: #fff; min-height: 100vh; padding: 20px; position: fixed; }
        .sidebar a { color: #ecf0f1; padding: 15px; display: block; text-decoration: none; border-radius: 8px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #3498db; }
        .content-area { margin-right: 260px; padding: 30px; }
        .card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .table thead { background: #2c3e50; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4"><i class="fa fa-graduation-cap fa-3x text-info"></i></div>
    <h5 class="text-center">نظام مدرستي</h5><hr>
    <a href="index.php"><i class="fa fa-home"></i> الرئيسية</a>
    <a href="classes.php" class="active"><i class="fa fa-school"></i> الفصول</a>
    <a href="students.php"><i class="fa fa-user-graduate"></i> الطلاب</a>
    <a href="teachers.php"><i class="fa fa-chalkboard-teacher"></i> المعلمون</a>
    <a href="courses.php"><i class="fa fa-book-open"></i> المواد</a>
    <a href="attendance.php"><i class="fa fa-calendar-check"></i> الحضور</a>
    <a href="grades.php"><i class="fa fa-clipboard-list"></i> الدرجات</a>
    <hr>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt"></i> خروج</a>
</div>

<div class="content-area">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>إدارة الفصول الدراسية</h3>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addModal">
            ➕ إضافة فصل جديد
        </button>
        <?php endif; ?>

    </div>

    <div class="card">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الفصل</th>
                    <th>معلم الفصل</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($classes as $class): ?>
                <tr>
                    <td><?= $class['id'] ?></td>
                    <td><?= htmlspecialchars($class['name']) ?></td>
                    <td><?= htmlspecialchars($class['teacher_name'] ?? 'لا يوجد') ?></td>
                    <td>

                        <?php if($_SESSION['role'] === 'admin'): ?>

                        <button class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $class['id'] ?>">
                            تعديل
                        </button>

                        <a href="?delete=<?= $class['id'] ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('هل أنتِ متأكدة؟')">
                           حذف
                        </a>

                        <?php endif; ?>

                    </td>
                </tr>

                <?php if($_SESSION['role'] === 'admin'): ?>
                <div class="modal fade" id="editModal<?= $class['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header"><h5>تعديل الفصل</h5></div>
                            <div class="modal-body">

                                <input type="hidden" name="id" value="<?= $class['id'] ?>">

                                <input type="text" name="name" class="form-control mb-2"
                                       value="<?= htmlspecialchars($class['name']) ?>" required>

                                <select name="teacher_id" class="form-control">
                                    <option value="">اختر المعلم</option>
                                    <?php foreach($teachers as $teacher): ?>
                                        <option value="<?= $teacher['id'] ?>"
                                            <?= $teacher['id']==$class['teacher_id']?'selected':'' ?>>
                                            <?= htmlspecialchars($teacher['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit_class" class="btn btn-primary">
                                    حفظ التغييرات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if($_SESSION['role'] === 'admin'): ?>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header"><h5>إضافة فصل جديد</h5></div>
            <div class="modal-body">

                <input type="text" name="name" class="form-control mb-2"
                       placeholder="اسم الفصل (مثال: ثالث ثانوي علمي)" required>

                <select name="teacher_id" class="form-control">
                    <option value="">اختر المعلم</option>
                    <?php foreach($teachers as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>">
                            <?= htmlspecialchars($teacher['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="modal-footer">
                <button type="submit" name="add_class" class="btn btn-primary">
                    إضافة الفصل
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
