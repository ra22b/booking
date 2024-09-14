<?php
session_start();
include('../db_connect.php'); // تأكد من أن db_connect يحتوي على إعدادات PDO

// تأكد من وجود معرف المستخدم في الجلسة
if (!isset($_SESSION['user_id'])) {
    die("لم تقم بتسجيل الدخول.");
}

// الحصول على معرف الطبيب من الجلسة
$id = $_SESSION['user_id'];

// استخدم استعلام مُعد مسبقًا لتجنب SQL Injection
$sql = "SELECT*,d.profile_image, d.doctor_id, d.doctor_name, d.gender, c.city_name, s.specialization_name
        FROM doctors d
        LEFT JOIN cities c ON d.city_id = c.city_id
        LEFT JOIN specializations s ON d.specialization_id = s.specialization_id
        WHERE doctor_id = :doctor_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':doctor_id', $id, PDO::PARAM_INT);
$stmt->execute();

// استرجاع بيانات الطبيب
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);

// إذا لم يتم العثور على الطبيب
if (!$doctor) {
    die("لم يتم العثور على معلومات للطبيب.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="DC.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>بطاقة الطبيب</title>
</head>
<body style="text-align: right">
    <!-- Sidebar Start -->
    <?php include('Sidebar.html'); ?>
    <!-- Sidebar End -->

    <!-- Page Content Start -->
    <div style="margin-right:15%">
        <div class="w3-container w3-center fade-in" style="background:#037272; color:white;">
            <h1> البيانات الشخصية</h1>
        </div>

        <div class="w3-container">
            <!-- التحقق من وجود بيانات الطبيب -->
            <?php if ($doctor): ?>
            <div class="doctor-card">
                <!-- صورة الطبيب -->
                <img src="../Admin/uploads/<?= htmlspecialchars($doctor['profile_image']) ?>" alt="صورة الطبيب">

                <!-- معلومات الطبيب -->
                <div class="doctor-info">
                    <strong>الاسم : </strong> <?= htmlspecialchars($doctor['doctor_name']) ?>
                </div>
                <div class="doctor-info">
                    <strong>التخصص : </strong> <?= htmlspecialchars($doctor['specialization_name']) ?>
                </div>
                <div class="doctor-info">
                    <strong>المدينة : </strong> <?= htmlspecialchars($doctor['city_name']) ?>
                </div>
                <div class="doctor-info">
                    <strong>عنوان العيادة : </strong> <?= htmlspecialchars($doctor['clinic_address']) ?>
                </div>
                <div class="doctor-info">
                    <strong>أيام العمل : </strong> <?= htmlspecialchars($doctor['working_days']) ?>
                </div>
                <div class="doctor-info">
                    <strong>ساعات العمل : </strong> <?= htmlspecialchars($doctor['working_hours']) ?>
                
                <div class="doctor-info">
                    <strong>رقم الهاتف : </strong> <?= htmlspecialchars($doctor['contact_number']) ?>
                </div>
                <div class="doctor-info">
                    <strong> البريد الإلكتروني : </strong> <?= htmlspecialchars($doctor['email']) ?>
                </div>
                <div class="doctor-info">
                    <strong>الجنس : </strong> <?= htmlspecialchars($doctor['gender']) ?>
                </div>

                <!-- أزرار تعديل -->
                <form action="editD.php" method="POST" >
                    <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($doctor['doctor_id']) ?>">
                    <button class="edit" type="submit" name="update">تعديل</button>
                   
                </form>
            </div>
            <?php else: ?>
                <p>لا توجد بيانات للطبيب.</p>
            <?php endif; ?>
        </div>
        <!-- Page Content End -->
    </div>
</body>
</html>
