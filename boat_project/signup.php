<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "boat_shop"; // تأكد من أن اسم قاعدة البيانات صحيح

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من نجاح الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// التحقق من إرسال البيانات عبر النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm-password"]);

    // التحقق من تطابق كلمتي المرور
    if ($password !== $confirm_password) {
        die("خطأ: كلمة المرور غير متطابقة.");
    }

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // التحقق من وجود البريد الإلكتروني مسبقًا في الجدول الجديد `register`
    $check_email = "SELECT id FROM register WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("هذا البريد الإلكتروني مسجل بالفعل.");
    }
    $stmt->close();

    // إدخال البيانات في الجدول الجديد `register`
    $sql = "INSERT INTO register (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "تم التسجيل بنجاح! <a href='login.html'>سجل الدخول</a>";
    } else {
        echo "حدث خطأ أثناء التسجيل: " . $conn->error;
    }

    $stmt->close();
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
