<?php
include('../db_connect.php')?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Home_admin.css">
    <title>الصفحة الرئيسسية</title>
</head>
<body>

<?php include('sidebar.html')?>

    <!-- Page Content Start -->
    <div style="margin-right:15%">
        <div class="w3-container w3-center fade-in" style="text-align: center; background:#037272; color:white;">
            <h1>إضافة طبيب</h1>
        </div>


        <div class="w3-container">
            <main>
                <div class="card-container">
                    <!-- 1 -->
                    <div class="card">
                        <div class="number">
                            <?php
                                $specializations_query = "SELECT * FROM specializations";
                                $stmt = $pdo->prepare($specializations_query);
                                $stmt->execute();
                                $Count_specializations = $stmt->rowCount(); 
                                echo $Count_specializations;
                            ?>
                        </div>
                        <div class="label">التخصصات الطبية</div>
                    </div>
                    <!-- 1 -->

                    <!-- 2 -->
                    <div class="card">
                        <div class="number">
                            <?php
                                $patient_query = "SELECT * FROM patients";
                                $stmt = $pdo->prepare($patient_query);
                                $stmt->execute();
                                $Count_patient = $stmt->rowCount(); 
                                echo $Count_patient;
                            ?>
                        </div>
                        <div class="label">المرضاء</div>
                    </div>
                    <!-- 2 -->

                    <!-- 3 -->
                    <div class="card">
                        <div class="number">
                            <?php
                                $doctor_query = "SELECT * FROM doctors";
                                $stmt = $pdo->prepare($doctor_query);
                                $stmt->execute();
                                $Count_doctor = $stmt->rowCount(); 
                                echo $Count_doctor;
                            ?>
                        </div>
                        <div class="label">الأطباء</div>
                    </div>
                    <!-- 3 -->

                    <!-- 4 -->
                    <div class="card">
                        <div class="number">
                            <?php
                                $cities_query = "SELECT * FROM cities";
                                $stmt = $pdo->prepare($cities_query);
                                $stmt->execute();
                                $Count_cities = $stmt->rowCount(); 
                                echo $Count_cities;
                            ?>
                        </div>
                        <div class="label">المدن</div>
                    </div>
                    <!-- 4 -->
                </div>


                <!--  -->
                <div class="card-container">
                    <!-- 2   1   -->
                    <div class="card">
                        <div class="number">
                            <?php
                                $admins_query = "SELECT * FROM admins";
                                $stmt = $pdo->prepare($admins_query);
                                $stmt->execute();
                                $Count_admins = $stmt->rowCount(); // تغيير هنا
                                echo $Count_admins; // استخدام المتغير الصحيح
                            ?>
                        </div>
                        <div class="label">المشرفين</div>
                    </div>
                    <!-- 2 -->

            </main>
        </div>
</div>

</body>
</html>