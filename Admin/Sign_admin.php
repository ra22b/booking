<?php 
  require '../db_connect.php';
  
 
  $message = '';
  // Verify the presence of the supervisor before transferring him
  if (isset($_POST['submit'])) {
      $email = $_POST['email'];
      $password = $_POST['password'];
  
      $sql = "SELECT * FROM admins WHERE email = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$email]);
      $admin = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if ($admin) {
          if (password_verify($password, $admin['password'])) {
              $_SESSION['admin'] = $email;
              header("Location: Home_admin.php");
              exit();
          } else {
              $message = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
          }
      } else {
         
          $message = 'البريد الإلكتروني غير موجود';
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Sign_admin.css">
    <title>Log-In</title>
    <script>
      // script start
        function validateForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var nameAdmin = document.getElementById("name_admin").value;
            var errorMessage = "";

            if (nameAdmin === "") {
                errorMessage += "الاسم مطلوب.\n";
            }
            if (email === "") {
                errorMessage += "الايميل مطلوب.\n";
            }
            if (password === "") {
                errorMessage += "كلمة المرور مطلوبة.\n";
            }

            if (errorMessage) {
                document.getElementById("error-message").innerText = errorMessage;
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <!-- login  form   -->
    <div class="form">
        <form action="Sign_admin.php" method="post" onsubmit="return validateForm()">
            <div class="title">تسجيل دخول المشرف</div>
            <div class="content">
                <div class="input">
                    <span>الاسم</span>
                    <input
                        type="text"
                        placeholder="الاسم"
                        id="name_admin"
                        name="name_admin"
                        max="50"
                    />
                </div>
                <div class="input">
                    <span>الايميل</span>
                    <input
                        type="text"
                        placeholder="الايميل"
                        id="email"
                        name="email"
                        required
                    />
                </div>
                <div class="input">
                    <span>كلمة المرور</span>
                    <input
                        type="password"
                        placeholder="كلمة المرور"
                        id="password"
                        name="password"
                        required
                    />
                </div>
                <div class="input submit">
                    <input type="submit" value="تسجيل الدخول" name="submit" />
                </div>
                <div id="error-message" style="color: red; text-align: center;">
                    <?php echo $message; ?>
                </div>
            </div>
        </form>
    </div>
</body>
</html>


<!-- insert admin data to DB -->
<?php
//   if (isset($_POST['submit'])) {
//     $email = $_POST['email'];
//     $password = $_POST['password'];
//     $name_admin = $_POST['name_admin'];
//     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
//     $sql = "INSERT INTO admins (name_admin, email, password) VALUES (?, ?, ?)";
//     $stmt = $pdo->prepare($sql);
//     $result = $stmt->execute([$name_admin, $email, $hashed_password]);
//     if ($result) {
//         echo "<script>alert('Data successfully inserted');</script>";
//     } else {
//         echo "<script>alert('An unexpected error occurred');</script>";
//     }
// }

?>