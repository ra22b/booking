<?php
session_start();
include('../db_connect.php');

// Check if the doctor is logged in
// التحقق من تسجيل دخول الطبيب
if (!isset($_SESSION['user_id'])) {
    die("يرجى تسجيل الدخول أولاً."); // If not logged in, terminate the script
    // إذا لم يتم تسجيل الدخول، يتم إيقاف البرنامج
}

// Get the doctor's ID from the session
// الحصول على معرف الطبيب من الجلسة
$doctor_id = $_SESSION['user_id'];

// SQL query to fetch appointments along with patient details
// استعلام SQL لجلب الحجوزات مع تفاصيل المرضى
$sql = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.stat, 
        p.patient_name, p.gender, p.age, p.email 
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        WHERE a.doctor_id = :doctor_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all appointments
// جلب جميع الحجوزات

// Check if the form has been submitted (POST request)
// التحقق إذا تم إرسال النموذج (طلب POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id']; // Get appointment ID
    // الحصول على معرف الحجز
    $action = $_POST['action']; // Get the action (accept or reject)
    // الحصول على الإجراء (قبول أو رفض)
    $patient_email = $_POST['patient_email']; // Get patient email
    // الحصول على بريد المريض الإلكتروني

    if ($action == 'accept') {
        // If the doctor accepts the appointment, update the status to "Confirmed"
        // إذا قبل الطبيب الحجز، يتم تحديث الحالة إلى "Confirmed"
        $update_sql = "UPDATE appointments SET stat = 'Confirmed' WHERE appointment_id = :appointment_id";
        $message = "تم تأكيد حجزك."; // Email message for confirmation
        // رسالة تأكيد البريد الإلكتروني
    } else {
        // If the doctor rejects the appointment, update the status to "Cancelled"
        // إذا رفض الطبيب الحجز، يتم تحديث الحالة إلى "Cancelled"
        $update_sql = "UPDATE appointments SET stat = 'Cancelled' WHERE appointment_id = :appointment_id";
        $message = "تم إلغاء حجزك."; // Email message for cancellation
        // رسالة إلغاء البريد الإلكتروني
    }

    // Execute the update query
    // تنفيذ استعلام التحديث
    $stmt = $pdo->prepare($update_sql);
    $stmt->bindParam(':appointment_id', $appointment_id);
    $stmt->execute();

}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>حجوزات المرضى</title>
</head>
<body style="text-align: right">
    <!-- Sidebar Start -->
    <!-- بدء الشريط الجانبي -->
    <?php include('Sidebar.html'); ?>
    <!-- Sidebar End -->
    <!-- نهاية الشريط الجانبي -->

    <div style="margin-right:15%">
        <div class="w3-container w3-center" style="background:#037272; color:white;">
            <h1>حجوزات المرضى</h1> <!-- Page title -->
            <!-- عنوان الصفحة -->
        </div>

        <div class="w3-container">
            <?php if (count($appointments) > 0): ?> <!-- Check if there are appointments -->
            <!-- التحقق من وجود حجوزات -->
                <table class="w3-table-all w3-card-4">
                    <thead>
                        <tr>
                            <th>اسم المريض</th>
                            <th>العمر</th>
                            <th>الجنس</th>
                            <th>تاريخ الحجز</th>
                            <th>وقت الحجز</th>
                            <th>حالة الحجز</th>
                            <th>إجراءات</th> <!-- Actions column -->
                            <!-- عمود الإجراءات -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?> <!-- Loop through appointments -->
                        <!-- حلقة تكرار الحجوزات -->
                            <tr>
                                <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                                <td><?= htmlspecialchars($appointment['age']) ?></td>
                                <td><?= htmlspecialchars($appointment['gender']) ?></td>
                                <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                                <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                <td><?= htmlspecialchars($appointment['stat']) ?: 'Pending' ?></td> <!-- Default status is Pending -->
                                <!-- الحالة الافتراضية هي "Pending" -->
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="appointment_id" value="<?= $appointment['appointment_id'] ?>">
                                        <input type="hidden" name="patient_email" value="<?= $appointment['email'] ?>">
                                        <button type="submit" name="action" value="accept" class="w3-button w3-green">قبول</button> <!-- Accept button -->
                                        <!-- زر القبول -->
                                        <button type="submit" name="action" value="reject" class="w3-button w3-red">إلغاء</button> <!-- Reject button -->
                                        <!-- زر الإلغاء -->
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?> <!-- End of loop -->
                        <!-- نهاية حلقة التكرار -->
                    </tbody>
                </table>
            <?php else: ?>
                <p>لا توجد حجوزات في الانتظار.</p> <!-- No appointments message -->
                <!-- رسالة عدم وجود حجوزات -->
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
