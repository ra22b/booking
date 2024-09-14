<?php
session_start();
include('../db_connect.php'); // تأكد من أن db_connect يحتوي على إعدادات PDO

// تأكد من أن المستخدم مسجل الدخول
if (!isset($_SESSION['user_id'])) {
    die("لم تقم بتسجيل الدخول.");
}

// الحصول على معرف الطبيب من الجلسة
$id = $_SESSION['user_id'];

// استخدم استعلام مُعد مسبقًا لتجنب SQL Injection
$sql = "SELECT *, d.doctor_id, d.doctor_name, d.gender, c.city_name, s.specialization_name, d.profile_image, d.working_days
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

// معالجة نموذج التحديث
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // استلام البيانات من النموذج والتحقق من وجود القيم في $_POST باستخدام null coalescing operator (??)
    $doctor_name = $_POST['doctor_name'] ?? null;
    $specialization_id = $_POST['specialization_id'] ?? null;
    $city_name = $_POST['city_name'] ?? null;
    $clinic_address = $_POST['clinic_address'] ?? null;
    $working_days = $_POST['working_days'] ?? []; // استخدام مصفوفة افتراضية
    $working_hours = $_POST['working_hours'] ?? null;
    $contact_number = $_POST['contact_number'] ?? null;
    $email = $_POST['email'] ?? null; 
    $gender = $_POST['gender'] ?? null;
    
    // تحقق مما إذا كانت كل القيم المطلوبة موجودة
    if ($doctor_name && $clinic_address && $working_hours && $contact_number && $email && $gender) {
        // تحديث بيانات الطبيب
        $update_sql = "UPDATE doctors SET doctor_name = :doctor_name, specialization_id = (SELECT specialization_id FROM specializations WHERE specialization_name = :specialization_name), city_id = (SELECT city_id FROM cities WHERE city_name = :city_name), clinic_address = :clinic_address, working_days = :working_days, working_hours = :working_hours, contact_number = :contact_number, email = :email, gender = :gender WHERE doctor_id = :doctor_id";
        $stmt = $pdo->prepare($update_sql);

        // تحويل مصفوفة أيام العمل إلى سلسلة نصية مفصولة بفواصل
        $working_days_string = implode(', ', $working_days);

        // ربط المتغيرات
        $stmt->bindParam(':doctor_name', $doctor_name);
        $stmt->bindParam(':specialization_name', $specialization_id);
        $stmt->bindParam(':city_name', $city_name);
        $stmt->bindParam(':clinic_address', $clinic_address);
        $stmt->bindParam(':working_days', $working_days_string);
        $stmt->bindParam(':working_hours', $working_hours);
        $stmt->bindParam(':contact_number', $contact_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':doctor_id', $id, PDO::PARAM_INT);

        // تنفيذ التحديث
        if ($stmt->execute()) {
            echo "تم تحديث البيانات بنجاح!";
            header("Location: DP.php"); // يمكنك إعادة التوجيه هنا إذا أردت
            exit();
        } else {
            $error = $stmt->errorInfo();
            echo "خطأ في التحديث: " . $error[2];
        }
    } else {
        echo "تأكد من ملء جميع الحقول.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editD.css">
    
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>التعديل على بيانات الطبيب</title>
</head>
<body style="text-align: right">
    <!-- Sidebar Start -->
    <?php include('Sidebar.html'); ?>
    <!-- Sidebar End -->

    <!-- Page Content Start -->
    <div style="margin-right:15%">
        <div class="w3-container w3-center fade-in" style="background:#037272; color:white;">
            <h1>البيانات الشخصية</h1>
        </div>

        <div class="w3-container">
            <div class="card">
                <h2>تعديل بيانات الطبيب</h2>
                <form method="POST" action="">

                    <div class="form-group">
                        <label for="doctor_name">اسم الطبيب:</label>
                        <input type="text" id="doctor_name" name="doctor_name" value="<?= htmlspecialchars($doctor['doctor_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                    <label for="specialization_id">التخصص:</label>
                    <select name="specialization_id" id="specializationSelect">
                        <option value="">اختر التخصص</option>
                        <?php
                        $sql = "SELECT specialization_name FROM specializations ORDER BY specialization_name ASC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($row['specialization_name'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['specialization_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        } else {
                            echo "<option value=''>لا توجد تخصصات متاحة</option>";
                        }
                        ?>
                    </select>
                    </div>


                    <div class="form-group">
                        <label for="city_id">المدينة:</label>
                        <select name="city_name" id="citiesSelect">
                        <option value="">اختر المدينة</option>
                        <?php
                        $sql = "SELECT city_name FROM cities ORDER BY city_name ASC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($row['city_name'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['city_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                        } else {
                            echo "<option value=''>لا توجد مدن متاحة</option>";
                        }
                        ?>
                    </select>

                    </div>
                    <div class="form-group">
                        <label for="clinic_address">عنوان العيادة:</label>
                        <input type="text" id="clinic_address" name="clinic_address" value="<?= htmlspecialchars($doctor['clinic_address'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="working_days">أيام العمل:</label>
                        <?php
                        $days = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                        // إذا كانت أيام العمل عبارة عن سلسلة نصية، حولها إلى مصفوفة
                        $selected_days = explode(', ', $doctor['working_days'] ?? '');
                        foreach ($days as $day) {
                            $checked = in_array($day, $selected_days) ? 'checked' : '';
                            echo "<label><input type='checkbox' name='working_days[]' value='$day' $checked> $day</label>";
                        }
                        ?>
                    </div>
                    <div class="form-group">
                        <label for="working_hours">ساعات العمل:</label>
                        <input type="text" id="working_hours" name="working_hours" value="<?= htmlspecialchars($doctor['working_hours'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">رقم التواصل:</label>
                        <input type="text" id="contact_number" name="contact_number" value="<?= htmlspecialchars($doctor['contact_number'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني:</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($doctor['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">الجنس:</label>
                        <select id="gender" name="gender" required>
                            <option value="Male" <?= ($doctor['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>ذكر</option>
                            <option value="Female" <?= ($doctor['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>أنثى</option>
                            <option value="Other" <?= ($doctor['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>أخرى</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="update" value="تحديث البيانات">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Page Content End -->
</body>
</html>
