<?php
/**
 * Phishing Education Project - Secure Login Handler
 * 
 * ⚠️ EDUCATIONAL PURPOSE ONLY
 * 
 * هذا الملف يوضح:
 * 1. كيف يعمل هجوم Phishing
 * 2. الثغرات الأمنية
 * 3. كيفية الحماية الصحيحة
 */

// بدء الجلسة الآمنة
session_start();

// إعدادات الأمان
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Security-Policy: default-src \'self\'');

// تسجيل المحاولات (للأغراض التعليمية)
error_reporting(E_ALL);
ini_set('display_errors', 0); // لا تعرض الأخطاء للمستخدم
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/security.log');

// ===================================
// 1️⃣ اتصال آمن بقاعدة البيانات
// ===================================

$servername = "localhost";
$db_username = "root"; // غيّر حسب نظامك
$db_password = ""; // غيّر حسب نظامك
$dbname = "phishing";

// إنشاء الاتصال مع معالجة الأخطاء الآمنة
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// تعيين المحارف الآمنة
$conn->set_charset("utf8mb4");

// التحقق من الاتصال (بدون كشف البيانات)
if ($conn->connect_error) {
    // ❌ لا تعرض الخطأ الحقيقي للمستخدم
    error_log("Database connection failed: " . $conn->connect_error);
    die("حدث خطأ في الاتصال. يرجى المحاولة لاحقاً.");
}

// ===================================
// 2️⃣ التحقق من طريقة الطلب
// ===================================

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die("طريقة غير مسموح بها");
}

// ===================================
// 3️⃣ التحقق من رمز CSRF
// ===================================

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    error_log("CSRF token mismatch attempt");
    die("أخطأ في التحقق الأمني. يرجى إعادة المحاولة.");
}

// ===================================
// 4️⃣ التحقق من وجود المدخلات
// ===================================

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    http_response_code(400);
    die("المدخلات مفقودة");
}

// ===================================
// 5️⃣ تنظيف وتحقق من صحة المدخلات
// ===================================

// تنظيف اسم المستخدم
$username = trim($_POST['username']);
$username = filter_var($username, FILTER_SANITIZE_STRING);

// التحقق من صيغة اسم المستخدم
if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    die("اسم المستخدم يجب أن يكون من 3 إلى 20 حرف (حروف وأرقام و _)");
}

// التحقق من طول كلمة المرور
$password = $_POST['password'];
if (strlen($password) < 6 || strlen($password) > 255) {
    die("كلمة المرور يجب أن تكون من 6 إلى 255 حرف");
}

// ===================================
// 6️⃣ معالجة البيانات بأمان (Prepared Statements)
// ===================================

// ✅ استخدام Prepared Statements لمنع SQL Injection
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    die("حدث خطأ تقني. يرجى المحاولة لاحقاً.");
}

// ربط المعاملات بشكل آمن
$stmt->bind_param("s", $username);

if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    die("حدث خطأ تقني. يرجى المحاولة لاحقاً.");
}

$stmt->store_result();

// ===================================
// 7️⃣ معالجة النتائج
// ===================================

if ($stmt->num_rows == 0) {
    // المستخدم جديد - إدراج جديد (للأغراض التعليمية فقط)
    
    $stmt->close();
    
    // ✅ تشفير كلمة المرور قبل الحفظ
    $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    // Prepared Statement للإدراج الآمن
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    
    if (!$stmt) {
        error_log("Insert prepare failed: " . $conn->error);
        die("حدث خطأ تقني. يرجى المحاولة لاحقاً.");
    }
    
    $stmt->bind_param("ss", $username, $hashed_password);
    
    if (!$stmt->execute()) {
        error_log("Insert failed: " . $stmt->error);
        die("فشل في حفظ البيانات. يرجى المحاولة لاحقاً.");
    }
    
    // تسجيل محاولة الوصول (للأغراض التعليمية)
    error_log("New user registered (educational demo): " . $username);
    
    // ✅ إعادة التوجيه الآمنة
    $stmt->close();
    $conn->close();
    
    // استخدام header redirect مع Location
    header("Location: thank_you.php", true, 302);
    exit();
    
} else {
    // المستخدم موجود بالفعل
    error_log("Duplicate user attempt (educational demo): " . $username);
    
    // لا نكشف ما إذا كان المستخدم موجوداً أم لا (أمان)
    // بدلاً من ذلك، نظهر رسالة عامة
    
    $stmt->close();
    $conn->close();
    
    // ⚠️ هنا يمكننا إضافة شرح تعليمي
    echo "
    <!DOCTYPE html>
    <html lang='ar' dir='rtl'>
    <head>
        <meta charset='UTF-8'>
        <title>تعليمي - معلومات مهمة</title>
        <style>
            body { font-family: Arial; padding: 20px; background: #f5f5f5; }
            .container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; }
            .warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px; }
            .info { background: #e7f3ff; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
            h2 { color: #333; }
            p { line-height: 1.6; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>⚠️ معلومات تعليمية</h2>
            
            <div class='warning'>
                <strong>هذا موقع توعوي فقط!</strong><br>
                أنت الآن رأيت كيف يمكن لموقع مزيف أن يخدعك!
            </div>
            
            <div class='info'>
                <h3>ماذا حدث للتو؟</h3>
                <p>أدخلت بيانات تسجيل دخول في موقع مزيف. في الواقع الفعلي، هذه البيانات كانت ستُحفظ عند المهاجم!</p>
            </div>
            
            <div class='info'>
                <h3>الثغرات الأمنية:</h3>
                <ul>
                    <li>✗ الموقع لم يبدو احترافياً</li>
                    <li>✗ الـ URL يحتوي على كلمة 'phishing'</li>
                    <li>✗ عدم وجود شهادة SSL (قفل أخضر)</li>
                    <li>✗ الموقع بسيط جداً بدون علامات ثقة</li>
                </ul>
            </div>
            
            <div class='info'>
                <h3>كيفية الحماية:</h3>
                <ul>
                    <li>✓ تفقد الـ URL بعناية</li>
                    <li>✓ ابحث عن القفل الأخضر 🔒</li>
                    <li>✓ تحقق من البريد الإلكتروني للعلامات المريبة</li>
                    <li>✓ لا تدخل بيانات حساسة إلا على الموقع الرسمي</li>
                    <li>✓ استخدم 2FA (المصادقة الثنائية)</li>
                </ul>
            </div>
            
            <a href='warning.html' style='display: inline-block; background: #4caf50; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin-top: 20px;'>← رجوع إلى التحذير</a>
        </div>
    </body>
    </html>
    ";
    exit();
}

?>
