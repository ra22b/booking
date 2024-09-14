<?php


session_start();
include('db_connect.php'); // تأكد من أن db_connect يحتوي على إعدادات PDO

// تأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['user_id'])) {
    die("لم تقم بتسجيل الدخول.");
}

// الحصول على معرف المريض من الجلسة
$patient_id = $_SESSION['user_id'];

// استعلام لجلب بيانات الحجز
$sql = "SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.stat, d.doctor_name, p.patient_name
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.doctor_id
        JOIN patients p ON a.patient_id = p.patient_id
        WHERE a.patient_id = :patient_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
$stmt->execute();

// استرجاع بيانات الحجز
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مواعيد الحجز</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
            flex-direction: column;
        }
        

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            width: 100%;
        }
        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .card h1 {
            font-size: 18px;
            margin: 10px 0;
        }
        .card p {
            margin: 5px 0;
            font-size: 16px;
        }
        .pending {
    background-color: #ffeeba;
    color: #856404;
}
    </style>
</head>
<body>
<center>
    <div class="container">
    <?php
if (count($appointments) > 0) {
    foreach ($appointments as $appointment) {
        // تحديد لون الكرت بناءً على حالة الحجز
        if ($appointment['stat'] === 'Confirmed') {
            $cardClass = 'confirmed';
            $statusText = 'تم قبول موعد الحجز';
        } elseif ($appointment['stat'] === 'Cancelled') {
            $cardClass = 'cancelled';
            $statusText = 'تم إلغاء موعد الحجز';
        } elseif (is_null($appointment['stat'])) {
            $cardClass = 'pending'; // يمكنك إضافة كلاس جديد لتصميم الطلبات المعلقة
            $statusText = 'الطلب معلق';
        } else {
            $cardClass = 'unknown'; // في حال كانت الحالة غير معروفة
            $statusText = 'حالة غير معروفة';
        }

        $doctorName = htmlspecialchars($appointment['doctor_name']);
        $appointmentDate = htmlspecialchars($appointment['appointment_date']);
        $appointmentTime = htmlspecialchars($appointment['appointment_time']);

        echo "<div class='card $cardClass'>";
        echo "<h1>$statusText</h1>";
        echo "<p>مع الطبيب: $doctorName</p>";
        echo "<p>بتاريخ: $appointmentDate</p>";
        echo "<p>في الوقت: $appointmentTime</p>";
        echo "</div>";
    }
} else {
    echo "<p>لا توجد مواعيد حالياً.</p>";
}
?>

    </div>
    </center>
</body>
</html>
