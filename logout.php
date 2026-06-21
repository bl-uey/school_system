<?php
require_once 'config.php';

// إنهاء الجلسة
session_unset();
session_destroy();

// إعادة التوجيه لصفحة تسجيل الدخول
header("Location: login.php");
exit;
?>