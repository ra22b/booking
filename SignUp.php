
<?php
session_start();

if (isset($_POST['submitbtn'])) {

  require 'db_connect.php';

    $error_message = '';

    $patient_name = htmlspecialchars(trim($_POST['fname']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    $contact_number = htmlspecialchars(trim($_POST['phone']));
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($email == false) {
        $error_message = 'Invalid email address';
    }

    if ($age === false) {
        $error_message = 'Invalid age';
    }

    if (!empty($error_message)) {
        return; 
    }
    $stm = $pdo->prepare("SELECT * FROM patients WHERE email = ?");
    $stm->execute([$_POST['email']]);
    $total = $stm->rowCount();

    if ($total) {
        $error_message = 'الحساب موجود بالفعل! أنت مسجل سابقاً';
    } else {
        // Check if passwords match
        if ($password != $cpassword) {
            $error_message = 'كلمة المرور غير متطابقة';
        } else {
            // Hash the password using password_hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stm = $pdo->prepare("INSERT INTO patients (patient_name, contact_number, gender, email, age, password) VALUES (?, ?, ?, ?, ?, ?)");
            $result = $stm->execute([$patient_name, $contact_number, $gender, $email, $age, $hashed_password]);

            if ($result) {
                $stm = $pdo->prepare("SELECT patient_id FROM patients WHERE email = ?");
                $stm->execute([$email]);
                $user = $stm->fetch(PDO::FETCH_ASSOC);

                // Store session data
                $_SESSION['user_id'] = $user['patient_id'];
                $_SESSION['role'] = 'user';

                // Redirect upon successful registration
                header("Location: /Booking/Home.php");
                exit(); 
            } else {
                $error_message = 'خطأ غير متوقع!!!!';
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <link rel="stylesheet" href="SignUp.css" />
  </head>

  <body>
    <main>
      <!-- start header -->
      <header>
        <a href="Home.php">
          <img src="image/logo_book.png" alt="book your" />
        </a>
      </header>
      <!-- end header -->

      <div class="title">اشتراك</div>
      <?php if (isset($error_message)): ?>
          <div class="errormessage"><?php echo $error_message; ?></div>
      <?php endif; ?>
      <form id="signupForm" action="SignUp.php" method="post" onsubmit="return validateForm()">
        <div class="mainContent">
          <div class="content-1">
            <div class="content">

              <div class="input">
                <span>الاسم</span>
                <input
                  type="text"
                  placeholder=" اسمك"
                  id="fname"
                  name="fname"
                  max="50"
                  value="<?php echo $f = (isset($_POST['fname'])) ?  htmlspecialchars($_POST['fname']) : "" ?>"
                />
                <small class="errormessage" id="fnameError"></small>
              </div>

              <div class="input">
                <span>الجنس</span>
                <select name="gender" id="gender" required>
                  <option value="" selected disabled hidden>اختر</option>
                  <option value="Male">أنثى</option>
                  <option value="Female">ذكر</option>
                </select>
                <small class="errormessage" id="genderError"></small>
              </div>

              <div class="input">
                <span>العمر</span>
                <input
                  type="number"
                  placeholder=" عمرك"
                  id="age"
                  name="age"
                  value="<?php echo $age = (isset($_POST['age'])) ?  htmlspecialchars($_POST['age']) : "" ?>"
                />
                <small class="errormessage" id="ageError"></small>
              </div>

              <div class="input">
                <span>الإيميل</span>
                <input
                  type="email"
                  placeholder=" ايميلك"
                  id="email"
                  name="email"
                  value="<?php echo $email = (isset($_POST['email'])) ?  htmlspecialchars($_POST['email']) : "" ?>"
                />
                <small class="" id="emailError"></small>
              </div>
            </div>
          </div>
          
          <div class="content-2">
            <div class="content">
              <div class="input">
                <span>رقم الهاتف</span>
                <input
                  type="text"
                  placeholder="رقم الهاتف"
                  id="phone"
                  name="phone"
                  required
                  value="<?php echo $contact_number = (isset($_POST['phone'])) ?  htmlspecialchars($_POST['phone']) : "" ?>"
                />
                <small class="error-message" id="phoneError"></small>
              </div>

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
                <small class="error-message" id="passwordError"></small>
              </div>

              <div class="input">
                <span>تأكيد كلمة المرور</span>
                <input
                  type="password"
                  placeholder="اعد كلمة المرور"
                  id="cpassword"
                  name="cpassword"
                  minlength="8"
                  required
                />
                <small class="error-message" id="cpasswordError"></small>
              </div>

              <div class="input submit">
                <input type="submit" value="اشتراك" name="submitbtn" />
              </div>

              <div class="signUp input">
                <p>هل تملك حساب سابق؟ <a href="./SignIn.php" class="a">تسجيل الدخول</a></p>
              </div>

            </div>
          </div>
        </div>
      </form>
    </main>

    <script>
      function validateForm() {
        let isValid = true;

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(element => {
          element.textContent = '';
        });

        // Validate name
        const name = document.getElementById('fname').value.trim();
        if (name === "") {
          document.getElementById('fnameError').textContent = "الاسم مطلوب";
          isValid = false;
        }

        // Validate gender
        const gender = document.getElementById('gender').value;
        if (gender === "") {
          document.getElementById('genderError').textContent = "الجنس مطلوب";
          isValid = false;
        }

        // Validate age
        const age = document.getElementById('age').value;
        if (age === "" || isNaN(age) || age <= 0) {
          document.getElementById('ageError').textContent = "يرجى إدخال عمر صحيح";
          isValid = false;
        }

        // Validate email
        const email = document.getElementById('email').value.trim();
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
          document.getElementById('emailError').textContent = "يرجى إدخال بريد إلكتروني صالح";
          isValid = false;
        }

        // Validate phone number
        const phone = document.getElementById('phone').value.trim();
        const phonePattern = /^[0-9]{8,14}$/;  // You can adjust this regex for the phone format you need
        if (!phonePattern.test(phone)) {
          document.getElementById('phoneError').textContent = "يرجى إدخال رقم هاتف صحيح";
          isValid = false;
        }

        // Validate password
        const password = document.getElementById('password').value;
        if (password.length < 8) {
          document.getElementById('passwordError').textContent = "يجب أن تكون كلمة المرور 8 أحرف على الأقل";
          isValid = false;
        }

        // Validate password confirmation
        const cpassword = document.getElementById('cpassword').value;
        if (password !== cpassword) {
          document.getElementById('cpasswordError').textContent = "كلمتا المرور غير متطابقتين";
          isValid = false;
        }

        return isValid;
      }
    </script>
  </body>
</html>
