<?php
// config.php
session_start();

// بيانات الاتصال بقاعدة البيانات
$host = '127.0.0.1';
$db   = 'school_system'; //  اسم قاعدة البيانات
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}


// تحقق إذا كان المستخدم مسجل دخول، وإلا يعيد توجيهه لصفحة تسجيل الدخول
function requireLogin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
        header("Location: login.php");
        exit;
    }
}

// إعادة التوجيه إذا كان المستخدم مسجل دخول بالفعل
function redirectIfLoggedIn() {
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '') {
        header("Location: index.php");
        exit;
    }
}
?>
