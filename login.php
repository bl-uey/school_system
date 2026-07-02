<?php
require_once 'config.php';
redirectIfLoggedIn(); 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // التحقق من اسم المستخدم وكلمة المرور
  if ($user && $user['password'] === $password) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];   
    header("Location: index.php");
    exit;

    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - نظام مدرستي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');
        
        body { 
            font-family: 'Cairo', sans-serif; 
            background: linear-gradient(135deg, #2c3e50, #3498db); 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            margin: 0;
        }
        .login-card { 
            background: #fff; 
            border-radius: 25px; 
            padding: 40px; 
            width: 380px; 
            text-align: center; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
        }
        .icon-logo { font-size: 50px; color: #3498db; margin-bottom: 20px; }
        .login-card h3 { color: #2c3e50; margin-bottom: 25px; font-weight: bold; }
        .form-control { 
            border-radius: 12px; 
            padding: 12px; 
            border: 1px solid #ddd; 
            margin-bottom: 15px;
        }
        .btn-login { 
            background: #3498db; 
            color: #fff; 
            border: none; 
            border-radius: 12px; 
            padding: 12px; 
            width: 100%; 
            font-weight: bold; 
            transition: 0.3s;
        }
        .btn-login:hover { background: #2980b9; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="icon-logo"><i class="fa fa-school"></i></div>
    <h3>مرحباً بك في نظام مدرستي</h3>
    
    <?php if($error): ?>
        <div class="alert alert-danger p-2 mb-3"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" class="form-control" placeholder="اسم المستخدم" required>
        <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
        <button type="submit" class="btn btn-login">تسجيل الدخول</button>
    </form>
</div>

</body>
</html>
