<?php
// Start the session
session_start();

// Include the database connection
include("db_connect.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: SignIn.php');
        exit;
    }

    // Validate appointment details
    if (isset($_POST['appointment_time']) && isset($_POST['appointment_date']) && isset($_POST['doctor_id'])) {
        $appointment_time = $_POST['appointment_time'];
        $appointment_date = date('Y-m-d', strtotime($_POST['appointment_date']));
        $docid = (int) $_POST['doctor_id']; // Ensure doctor_id is an integer

        // Retrieve patient ID from session
        $patient = $_SESSION['user_id'];

        // Query to get patient_id using PDO
        $sql = "SELECT patient_id FROM patients WHERE patient_id = :patient";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':patient', $patient, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $patid = $result['patient_id'];

            // Check if the appointment slot is available
            $sql = "SELECT * FROM appointments WHERE doctor_id = :doctor_id AND patient_id = :patient_id AND appointment_date = :appointment_date AND appointment_time = :appointment_time";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':doctor_id', $docid, PDO::PARAM_INT);
            $stmt->bindParam(':patient_id', $patid, PDO::PARAM_INT);
            $stmt->bindParam(':appointment_date', $appointment_date, PDO::PARAM_STR);
            $stmt->bindParam(':appointment_time', $appointment_time, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() >= 1) {
                // Appointment time is not available
                echo "<script>alert('تم حجز الموعد من مريض سابق اختر موعد اخر');</script>";
            } else {
                // Insert the new appointment
                $sql = "INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time) VALUES (:doctor_id, :patient_id, :appointment_date, :appointment_time)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':doctor_id', $docid, PDO::PARAM_INT);
                $stmt->bindParam(':patient_id', $patid, PDO::PARAM_INT);
                $stmt->bindParam(':appointment_date', $appointment_date, PDO::PARAM_STR);
                $stmt->bindParam(':appointment_time', $appointment_time, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    // Success message
                    echo "<script>alert('تم الاضافة بنجاح');</script>";
                } else {
                    // Error message
                    echo "<script>alert('خطأ في حجز الموعد');</script>";
                }
            }
        } else {
            // Patient not found
            echo "<script>alert('المريض غير موجود');</script>";
        }
    } else {
        // Missing appointment details
        echo "<script>alert('من فضلك اختر اليوم والوقت');</script>";
    }
}
?>
<?php include('header.php') ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="Booking.css" />
    <title>Book Appointment</title>
  </head>
  <body>
  
    <main>
      <div class="docInfo">
        <div class="doc">
        <?php 
        // Check if doctor_id is set in the URL
        if (isset($_GET['doctor_id'])) {
        $doctor_id = (int) $_GET['doctor_id']; // Ensure doctor_id is an integer

        // Query to get doctor details
        $sql = "SELECT *, d.doctor_id, d.doctor_name, d.gender, c.city_name, s.specialization_name, d.profile_image
                FROM doctors d
                LEFT JOIN cities c ON d.city_id = c.city_id
                LEFT JOIN specializations s ON d.specialization_id = s.specialization_id
                WHERE d.doctor_id = :doctor_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $fname = htmlspecialchars($row['doctor_name']); // Protect against XSS
            $speciality = htmlspecialchars($row['specialization_name']);
            $profile_image = htmlspecialchars($row['profile_image']); // Doctor's profile image

            echo "
            <div>
                <img src='Admin/uploads/{$profile_image}' alt='Doctor Image' />
            </div>
            <div class='docinfo'>
                <span class='name'>د. {$fname}</span>
                <span> التخصص: {$speciality}</span>
            </div>";
        } else {
            echo "<p>Doctor not found.</p>";
        }
        } else {
            echo "<p>Invalid doctor ID.</p>";
        }
        ?>
            </div>
    
 
          </div>
          <form method="post">
                <div class="schedule">
                    <div>
                        <span class="datelabel" for="date">اختر تاريخ</span>
                        <input type="date" name="appointment_date" id="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="dates" id="calendar">
                        <?php 
                        $doctor_id = (int) $_GET['doctor_id'];
                        
                        echo '
                        <div class="slots">
                            <div class="title">
                                <span>الأوقات المتاحة</span>
                            </div>
                            <div class="timings">
                                <div class="morning box">
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=10:00" class=""><span class="slot">10:00 ص</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=10:30" class=""><span class="slot">10:30 ص</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=11:00" class=""><span class="slot">11:00 ص</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=11:30" class=""><span class="slot">11:30 ص</span></a>
                                </div>
                                <div class="afternoon box">
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=12:30" class=""><span class="slot">12:30 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=13:00" class=""><span class="slot">01:00 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=13:30" class=""><span class="slot">01:30 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=14:00" class=""><span class="slot">02:00 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=14:30" class=""><span class="slot">02:30 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=15:00" class=""><span class="slot">03:00 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=15:30" class=""><span class="slot">03:30 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=16:00" class=""><span class="slot">04:00 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=16:30" class=""><span class="slot">04:30 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=17:00" class=""><span class="slot">05:00 م</span></a>
                                </div>
                                <div class="evening box">
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=18:00" class=""><span class="slot">06:00 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=18:30" class=""><span class="slot">06:30 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=19:00" class=""><span class="slot">07:00 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=19:30" class=""><span class="slot">07:30 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=20:00" class=""><span class="slot">08:00 م</span></a>
                                    <a href="Booking.php?doctor_id='.$doctor_id.'&time=20:30" class=""><span class="slot">08:30 م</span></a>
                                </div>
                            </div>
                        </div>';
                        ?>
    
                        <div class="input dateInp">
                            <input type="hidden" value="<?php if (isset($_GET['time'])) { echo htmlspecialchars($_GET['time']); } ?>" name="appointment_time" />
                        </div>
    
                        <div class="input">
                            <input type="hidden" value="<?php if (isset($_GET['doctor_id'])) { echo htmlspecialchars($_GET['doctor_id']); } ?>" name="doctor_id" />
                        </div>
                        
                        <div class="input submit">
                            <input type="submit" value="احجز م" name="submit" />
                        </div>
                    </div>
                </form>
    </main>

    <script src="./Booking.js"></script>
  </body>
</html>
