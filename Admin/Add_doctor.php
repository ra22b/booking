<?php
session_start();
include('../db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Add_doctor.css">
    <link rel="stylesheet" href="Home_page.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>إضافة طبيب</title>

    <script>
        function validateForm() {
            let fname = document.getElementById("fname").value;
            let gender = document.getElementById("gender").value;
            let city = document.getElementById("cities").value;
            let specialty = document.getElementById("specialty").value;
            let clinicAddress = document.getElementById("clinic_address").value;
            let email = document.getElementById("email").value;
            let phone = document.getElementById("phone").value;
            let password = document.getElementById("password").value;
            let cpassword = document.getElementById("cpassword").value;
            let workingDays = document.querySelectorAll('input[name="working_days[]"]:checked');
            let workingDaysArray = [];
            workingDays.forEach((day) => {
                workingDaysArray.push(day.value);
            });

            let errorMessage = '';

            if (!fname || !gender || !city || !specialty || !clinicAddress || !email || !phone || !password || !cpassword) {
                errorMessage += "يرجى ملء جميع الحقول.<br>";
            }

            if (password !== cpassword) {
                errorMessage += "كلمة المرور لا تتطابقان.<br>";
            }

            if (workingDaysArray.length === 0) {
                errorMessage += "يرجى اختيار أيام العمل.<br>";
            }

            if (errorMessage) {
                document.getElementById("error-message").innerHTML = errorMessage;
                return false;
            }

            return true;
        }
    </script>

</head>
<body>
    <!-- Sidebar Start -->
    <?php
    include('Sidebar.html');
    ?>
    <!-- Sidebar end -->

    <!-- Page Content Start -->
    <div style="margin-right:15%">
        <div class="w3-container w3-center fade-in" style="text-align: center; background:#037272; color:white;">
            <h1>إضافة طبيب</h1>
        </div>

        <div class="w3-container">
            <p class="title">بيانات الطبيب</p>

            <!--start message error -->
            <div id="error-message" style="color:red; text-align:center"></div>
            <!--end message error -->

            <!-- form -->
            <form action="Add.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="mainContent">
                    <div class="content-1">
                        <div class="content">
                            <!-- الاسم -->
                            <div class="input">
                                <span>الاسم</span>
                                <input
                                    type="text"
                                    placeholder="اسم الطبيب"
                                    id="fname"
                                    name="fname"
                                    max="50"
                                    value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required
                                />
                            </div>

                            <!-- الجنس -->
                            <div class="input">
                                <span>الجنس</span>
                                <select name="gender" id="gender" required>
                                    <option value="" <?php echo !isset($_POST['gender']) ? 'selected' : ''; ?> disabled hidden>اختر</option>
                                    <option value="Male" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Male' ? 'selected' : ''; ?>>أنثى</option>
                                    <option value="Female" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Female' ? 'selected' : ''; ?>>ذكر</option>
                                </select>
                            </div>

                            <!-- المدينة -->
                            <div class="input">
                                <span>المدينة</span>
                                <select name="cities" id="cities" required>
                                    <option value="">اختر المدينة</option>
                                    <?php
                                    $sql = "SELECT city_name FROM cities ORDER BY city_name ASC";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = (isset($_POST['cities']) && $_POST['cities'] === $row['city_name']) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($row['city_name'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($row['city_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>لا توجد مدن متاحة</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- التخصص -->
                            <div class="input">
                                <span>التخصص الطبي</span>
                                <select name="specialty" id="specialty" required>
                                    <option value="">اختر التخصص</option>
                                    <?php
                                    $sql = "SELECT specialization_name FROM specializations ORDER BY specialization_name ASC";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = (isset($_POST['specialty']) && $_POST['specialty'] === $row['specialization_name']) ? 'selected' : '';
                                            echo "<option value='" . htmlspecialchars($row['specialization_name'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($row['specialization_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>لا توجد تخصصات متاحة</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- تفاصيل الاختصاص -->
                            <div class="input">
                                <span>تفاصيل التخصص</span>
                                <input
                                    type="text"
                                    placeholder="تفاصيل التخصص"
                                    id="Description_spe"
                                    name="Description_spe"
                                    max="50"
                                    value="<?php echo isset($_POST['Description_spe']) ? htmlspecialchars($_POST['Description_spe'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required
                                />
                            </div>
                            <!-- عنوان العيادة -->
                            <div class="input">
                                <span>عنوان العيادة</span>
                                <input
                                    type="text"
                                    placeholder="عنوان العيادة"
                                    id="clinic_address"
                                    name="clinic_address"
                                    max="50"
                                    value="<?php echo isset($_POST['clinic_address']) ? htmlspecialchars($_POST['clinic_address'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required
                                />
                            </div>

                            <!-- ايميل -->
                            <div class="input">
                                <span>الإيميل</span>
                                <input
                                    type="email"
                                    placeholder="ايميل الطبيب"
                                    id="email"
                                    name="email"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <div class="content-2">
                        <div class="content">
                            <!-- ايام الدوام -->
                            <div class="input">
                                <span>أيام الدوام</span>
                            </div>
                            <div class="checkbox-group" style="color:#207c79; padding:0px 0 15px 0">
                                <?php
                                $days = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                                foreach ($days as $day) {
                                    $checked = isset($_POST['working_days']) && in_array($day, $_POST['working_days']) ? 'checked' : '';
                                    echo "<label><input type='checkbox' name='working_days[]' value='$day' $checked> $day</label>";
                                }
                                ?>
                            </div>
                            <div class="input">
                                <span>ساعات الدوام</span>
                                <input
                                    type="text"
                                    placeholder="ساعات الدوام"
                                    id="working_hours"
                                    name="working_hours"
                                    max="50"
                                    value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required
                                />
                            </div>
                            <!-- صورة-->
                            <div class="input">
                                <span>صورة الملف</span>
                                <input class="image"
                                    type="file"
                                    placeholder="اختر صورة"
                                    id="profile_image"
                                    name="profile_image"
                                    required
                                />
                            </div>

                            <!-- رقم الهاتف -->
                            <div class="input">
                                <span>رقم الهاتف</span>
                                <input
                                    type="text"
                                    placeholder="رقم الهاتف"
                                    id="phone"
                                    name="phone"
                                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required
                                />
                            </div>

                            <!-- كلمة المرور -->
                            <div class="input">
                                <span>كلمة المرور</span>
                                <input
                                    type="password"
                                    placeholder="كلمة المرور"
                                    id="password"
                                    name="password"
                                    minlength="8"
                                    required
                                />
                            </div>

                            <!-- تأكيد كلمة المرور -->
                            <div class="input">
                                <span>تأكيد كلمة المرور</span>
                                <input
                                    type="password"
                                    placeholder="اعد كتابة كلمة المرور"
                                    id="cpassword"
                                    name="cpassword"
                                    minlength="8"
                                    required
                                />
                            </div>

                            <div class="input submit">
                                <input type="submit" value="إضافة" name="submit_doc" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- form -->
        </div>
    </div>

    <!-- Page Content end -->
</body>
</html>
