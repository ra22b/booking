<?php
    include('../db_connect.php');    
    $error_messages = array();
    
    if (isset($_POST['submit_doc'])) {
        // Retrieve form data
        $fname = isset($_POST['fname']) ? trim($_POST['fname']) : '';
        $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
        $city = isset($_POST['cities']) ? trim($_POST['cities']) : '';
        $specialty = isset($_POST['specialty']) ? trim($_POST['specialty']) : '';
        $clinic_address = isset($_POST['clinic_address']) ? trim($_POST['clinic_address']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $description_spe = isset($_POST['description_spe']) ? trim($_POST['description_spe']) : '';
        $working_hours = isset($_POST['working_hours']) ? trim($_POST['working_hours']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $cpassword = isset($_POST['cpassword']) ? trim($_POST['cpassword']) : '';
        $working_days = isset($_POST['working_days']) ? $_POST['working_days'] : array();
    
        // Validate data
        if (empty($fname) || empty($gender) || empty($city) || empty($specialty) || empty($clinic_address) || empty($email) || empty($phone) || empty($password) || empty($cpassword)) {
            $error_messages[] = "يرجى ملء جميع الحقول.";
        }
    
        if ($password !== $cpassword) {
            $error_messages[] = "كلمة المرور وتأكيد كلمة المرور لا يتطابقان.";
        }
    
        if (empty($working_days)) {
            $error_messages[] = "يرجى اختيار أيام العمل.";
        }
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_messages[] = "البريد الإلكتروني غير صالح.";
        }
    
        // Handle file upload
        $profile_image = '';
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $target_dir = __DIR__ . "/uploads/";
            $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
            
            // Ensure the uploads directory exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $profile_image = basename($_FILES["profile_image"]["name"]);
            } else {
                $error_messages[] = "فشل تحميل الصورة.";
            }
        } else {
            $error_messages[] = "يرجى تحميل صورة الملف.";
        }
    
        // If there are errors, display them
        if (!empty($error_messages)) {
            foreach ($error_messages as $message) {
                echo "<p>$message</p>";
            }
            exit();
        }
    
        $working_days_string = implode(',', $working_days);
    
        // Fetch city_id
        $city_stmt = $pdo->prepare("SELECT city_id FROM cities WHERE city_name = ?");
        $city_stmt->execute([$city]);
        $city_id = $city_stmt->fetchColumn();
    
        // Fetch specialization_id
        $specialty_stmt = $pdo->prepare("SELECT specialization_id FROM specializations WHERE specialization_name = ?");
        $specialty_stmt->execute([$specialty]);
        $specialization_id = $specialty_stmt->fetchColumn();
    
        // Insert data into doctors table
        $sql = "INSERT INTO doctors (doctor_name, gender, city_id, specialization_id, clinic_address, email, contact_number, password, profile_image, working_days, working_hours, description_spe) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        try {
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$fname, $gender, $city_id, $specialization_id, $clinic_address, $email, $phone, password_hash($password, PASSWORD_DEFAULT), $profile_image, $working_days_string, $working_hours, $description_spe]);
    
            // Redirect to s.php after successful insertion
            if ($result) {
                header("Location: Add_doctor.php");
                exit();
            } else {
                echo "حدث خطأ أثناء إدخال البيانات.";
            }
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
 
    









/**
 * city
 */

if (isset($_POST['submit_cit'])) {
        $city_name = $_POST['city_name'];
    
        if (!empty($city_name)) {
            $check_query = "SELECT * FROM cities WHERE city_name = :city_name";
            $stmt = $pdo->prepare($check_query);
            $stmt->bindParam(':city_name', $city_name, PDO::PARAM_STR);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $message = "اسم المدينة موجود بالفعل.";
            } else {
                $query = "INSERT INTO cities (city_name) VALUES (:city_name)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':city_name', $city_name, PDO::PARAM_STR);
    
                if ($stmt->execute()) {
                    $message = "تمت إضافة المدينة بنجاح!";
                } else {
                    $message = "حدث خطأ أثناء إضافة المدينة.";
                }
            }
        } else {
            $message = "الرجاء إدخال اسم المدينة.";
        }
    
        // إعادة التوجيه مع الرسالة في الرابط
        ("Location: Add_cit_spe.php?message=" . urlencode($message));
        exit();
}

/**
 * specializations
 */

 
 if (isset($_POST['submit_spe'])) {
     $specializations = $_POST['specializations'];
 
     if (!empty($specializations)) {
         // التحقق من وجود التخصص في قاعدة البيانات
         $check_query = "SELECT * FROM specializations WHERE specialization_name = :specialization_name";
         $stmt = $pdo->prepare($check_query);
         $stmt->bindParam(':specialization_name', $specializations, PDO::PARAM_STR);
         $stmt->execute();
 
         if ($stmt->rowCount() > 0) {
             $message = "التخصص موجود بالفعل.";
         } else {
             // إدخال التخصص إذا لم يكن موجودًا
             $query = "INSERT INTO specializations (specialization_name) VALUES (:specialization_name)";
             $stmt = $pdo->prepare($query);
             $stmt->bindParam(':specialization_name', $specializations, PDO::PARAM_STR);
 
             if ($stmt->execute()) {
                 $message = "تمت إضافة التخصص بنجاح!";
             } else {
                 $message = "حدث خطأ أثناء إضافة التخصص.";
             }
         }
     } else {
         $message = "الرجاء إدخال اسم التخصص.";
     }
 
     // إعادة التوجيه مع الرسالة في الرابط
     ("Location: Add_cit_spe.php?message=" . urlencode($message));
     exit();
 }

 


?>
