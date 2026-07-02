<?php
require_once 'config.php';
requireLogin();

// منع غير المسجلين
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ADD ATTENDANCE (admin فقط)
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['add_attendance'])){

        if($_SESSION['role'] !== 'admin'){
            die("غير مسموح لك بإضافة سجل حضور");
        }

        $stmt = $pdo->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['student_id'], $_POST['date'], $_POST['status']]);
        header("Location: attendance.php?msg=added"); exit;
    }

    // EDIT (admin فقط)
    if(isset($_POST['edit_attendance'])){

        if($_SESSION['role'] !== 'admin'){
            die("غير مسموح لك بتعديل السجلات");
        }

        $stmt = $pdo->prepare("UPDATE attendance SET student_id=?, date=?, status=? WHERE id=?");
        $stmt->execute([$_POST['student_id'], $_POST['date'], $_POST['status'], $_POST['id']]);
        header("Location: attendance.php?msg=updated"); exit;
    }
}

// DELETE (admin فقط)
if(isset($_GET['delete'])){

    if($_SESSION['role'] !== 'admin'){
        die("غير مسموح لك بالحذف");
    }

    $stmt = $pdo->prepare("DELETE FROM attendance WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: attendance.php?msg=deleted"); exit;
}

$attendance_records = $pdo->query("
SELECT a.*, s.name AS student_name 
FROM attendance a 
JOIN students s ON a.student_id=s.id 
ORDER BY a.date DESC
")->fetchAll();

$students = $pdo->query("SELECT id, name FROM students")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الحضور - نظام مدرستي</title>
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
        .badge-حاضر { background: #27ae60; color: white; }
        .badge-غائب { background: #e74c3c; color: white; }
        .badge-متأخر { background: #f39c12; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <h5 class="text-center"><i class="fa fa-school"></i> نظام مدرستي</h5><hr>
    <a href="index.php"><i class="fa fa-home"></i> الرئيسية</a>
    <a href="attendance.php" class="active"><i class="fa fa-check-circle"></i> سجل الحضور</a>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt"></i> خروج</a>
</div>

<div class="content-area">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>سجل الحضور والغياب</h3>

        <?php if($_SESSION['role'] === 'admin'): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            ➕ إضافة سجل جديد
        </button>
        <?php endif; ?>
    </div>

    <div class="card">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الطالب</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($attendance_records as $record): ?>
                <tr>
                    <td><?= $record['id'] ?></td>
                    <td><?= htmlspecialchars($record['student_name']) ?></td>
                    <td><?= $record['date'] ?></td>
                    <td><span class="badge badge-<?= $record['status'] ?>"><?= $record['status'] ?></span></td>
                    <td>

                        <?php if($_SESSION['role'] === 'admin'): ?>

                        <button class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $record['id'] ?>">
                            تعديل
                        </button>

                        <a href="?delete=<?= $record['id'] ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('تأكيد الحذف؟')">
                           حذف
                        </a>

                        <?php endif; ?>

                    </td>
                </tr>

                <?php if($_SESSION['role'] === 'admin'): ?>
                <div class="modal fade" id="editModal<?= $record['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header"><h5>تعديل السجل</h5></div>
                            <div class="modal-body">

                                <input type="hidden" name="id" value="<?= $record['id'] ?>">

                                <select name="student_id" class="form-control mb-2">
                                    <?php foreach($students as $s): ?>
                                        <option value="<?= $s['id'] ?>" <?= $s['id']==$record['student_id']?'selected':'' ?>>
                                            <?= $s['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <input type="date" name="date" class="form-control mb-2" value="<?= $record['date'] ?>" required>

                                <select name="status" class="form-control">
                                    <option value="حاضر" <?= $record['status']=='حاضر'?'selected':'' ?>>حاضر</option>
                                    <option value="غائب" <?= $record['status']=='غائب'?'selected':'' ?>>غائب</option>
                                    <option value="متأخر" <?= $record['status']=='متأخر'?'selected':'' ?>>متأخر</option>
                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="edit_attendance" class="btn btn-primary">
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
            <div class="modal-header"><h5>إضافة سجل حضور</h5></div>
            <div class="modal-body">

                <select name="student_id" class="form-control mb-2">
                    <option value="">اختر الطالب</option>
                    <?php foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="date" name="date" class="form-control mb-2" required>

                <select name="status" class="form-control">
                    <option value="حاضر">حاضر</option>
                    <option value="غائب">غائب</option>
                    <option value="متأخر">متأخر</option>
                </select>

            </div>
            <div class="modal-footer">
                <button type="submit" name="add_attendance" class="btn btn-primary">
                    حفظ
                </button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
