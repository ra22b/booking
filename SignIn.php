
<?php
  session_start();
  require('./db_connect.php');

  $error_message = '';

  if (isset($_POST['submitbtn'])) {
      $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
      $password = $_POST['password'];

      if (!$email) {
          $error_message = "البريد الإلكتروني غير صالح.";
      } else {
          // doctor table
          $doctor_query = "SELECT * FROM doctors WHERE email = ?";
          $stmt = $pdo->prepare($doctor_query);
          $stmt->execute(array($email));

          if ($stmt->rowCount() > 0) {
              $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

              // doctor password
              if (password_verify($password, $doctor['password'])) {
                  $_SESSION['user_id'] = $doctor['doctor_id'];
                  $_SESSION['role'] = 'doctor';
                  header('Location: doctor/DP.php');
                  exit();
              } else {
                  $error_message = "كلمة المرور غير صحيحة.";
              }
          } else { // تأكد من إضافة هذا الإغلاق لجملة if الخاصة بالأطباء
              // patients table
              $user_query = "SELECT * FROM patients WHERE email = ?";
              $stmt = $pdo->prepare($user_query);
              $stmt->execute(array($email));

              if ($stmt->rowCount() > 0) {
                  $user = $stmt->fetch(PDO::FETCH_ASSOC);

                  // patients password
                  if (password_verify($password, $user['password'])) {
                      $_SESSION['user_id'] = $user['patient_id'];
                      $_SESSION['role'] = 'user';
                      header('Location: home.php');
                      exit();
                  } else {
                      $error_message = "كلمة المرور غير صحيحة.";
                  }
              } else {
                  $error_message = "اسم المستخدم غير موجود.";
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
    <link rel="stylesheet" href="SignIn.css" />
    <title>تسجيل الدخول</title>
  </head>

  <body>
    <header>
      <a href="Home.php">
        <img src="image/logo_book.png">
      </a>  
    </header>

    <main class="main">
      <aside>
        <img src="image/home.jpg" alt="image" srcset="" />
      </aside>
      <div class="container">
        <div class="form">
          <form action="SignIn.php" method="POST" onsubmit="return validateForm();">
            <div class="title">تسجيل الدخول</div>

            <!-- Start error message from PHP -->
            <?php if (!empty($error_message)): ?>
              <div class="errormessage"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <!-- End error message -->

            <div class="content">
              <div class="input">
                <span>ايميل</span>
                <input type="text" placeholder="ايميل" id="email" name="email" required />
                <div class="error-message" id="emailError"></div> <!-- Error for email -->
              </div>
              <div class="input">
                <span>كلمة المرور</span>
                <input type="password" placeholder="كلمة المرور" id="password" name="password" required />
                <div class="error-message" id="passwordError"></div> <!-- Error for password -->
              </div>
              <div class="input submit">
                <input type="submit" value="تسجيل الدخول" name="submitbtn" />
              </div>
              <div class="signUp input">
                <p>هل تملك حساب ! <a href="./SignUp.php">اشتراك</a></p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>

    <script>
      function validateForm() {
        let isValid = true;

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(element => {
          element.textContent = '';
        });

        // Validate email
        const email = document.getElementById('email').value.trim();
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
          document.getElementById('emailError').textContent = "يرجى إدخال بريد إلكتروني صالح";
          isValid = false;
        }

        // Validate password
        const password = document.getElementById('password').value;
        if (password.length < 8) {
          document.getElementById('passwordError').textContent = "يجب أن تكون كلمة المرور 8 أحرف على الأقل";
          isValid = false;
        }

        return isValid;
      }
    </script>
  </body>
</html>
