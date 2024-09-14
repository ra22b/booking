<?php
    include("db_connect.php");
    session_start(); 

    // Ensure that doctor_id is passed via GET
    $doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;

    if ($doctor_id <= 0) {
        echo "<p style='text-align:center'>Invalid doctor ID.</p>";
        exit();
    }

    // Build SQL query to fetch doctor details
    $sql = "SELECT *, d.doctor_id, d.doctor_name, d.gender, c.city_name, s.specialization_name, d.profile_image, d.working_days
            FROM doctors d
            LEFT JOIN cities c ON d.city_id = c.city_id
            LEFT JOIN specializations s ON d.specialization_id = s.specialization_id
            WHERE d.doctor_id = :doctor_id"; // Use :doctor_id placeholder

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            padding: 20px;
            margin-top: 140px;
        }

        .card {
            box-shadow: 0 4px 8px 0 #037272;
            max-width: 700px;
            height: auto;
            margin: auto;
            font-family: Arial, sans-serif;
            display: flex; /* Align items horizontally within the card */
            align-items: center; /* Vertically center items in the card */
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 20px;
            background-color: #fff;
            font-size: 20px;
            color: #037272;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .card img {
            margin-right: 30px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }

        .card-content {
            flex: 1;
            text-align: right;
            direction: rtl;
        }

        .button {
            border: none;
            outline: 0;
            display: inline-block;
            padding: 8px;
            color: white;
            background-color: #037272;
            text-align: center;
            cursor: pointer;
            width: 50%;
            font-size: 18px;
            transition: background-color 0.3s;
            border-radius: 15px;
        }

        .button:hover {
            background-color: #1d8686cf;
        }

        a {
            text-decoration: none;
            font-size: 22px;
            color: #037272;
            transition: color 0.3s;
        }

        a:hover {
            color: #037272;
        }

        .title {
            text-align: center;
            color: #037272;
            font-size: 40px;
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                text-align: center;
            }

            .card img {
                margin-right: 0;
                margin-bottom: 10px; /* Fixed typo */
                width: 150px;
                height: 150px;
            }

            .button {
                width: 100%;
            }

            .title {
                font-size: 30px;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 10px;
            }

            .card-content {
                text-align: center;
                font-size: 12px;
            }

            .button {
                font-size: 16px;
            }

            .title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <h2 class="title">معلومات الطبيب</h2>

    <?php
    if (count($results) > 0) {
        foreach ($results as $row) {
            echo '<div class="card">';
            echo '<img src="Admin/uploads/' . htmlspecialchars($row["profile_image"]) . '" alt="Doctor Image">';
            echo '<div class="card-content">';
            echo '<h1>د. ' . htmlspecialchars($row["doctor_name"]) . '</h1>';
            echo '<p><b>التخصص:</b> ' . htmlspecialchars($row["specialization_name"]) . '</p>';
            echo '<p><b>المدينة:</b> ' . htmlspecialchars($row["city_name"]) . '</p>';
            echo '<p><b>تفاصيل أكثر: </b> ' . htmlspecialchars($row["description_spe"]) . '</p>';
            echo '<p><b>عنوان الطبيب: </b> ' . htmlspecialchars($row["clinic_address"]) . '</p>';
            echo '<p><b>أيام الدوام: </b> ' . htmlspecialchars($row["working_days"]) . '</p>';
            echo '<p><b>ساعات الدوام: </b> ' . htmlspecialchars($row["working_hours"]) . '</p>';
            echo '<p><b>للتواصل: </b> ' . htmlspecialchars($row["contact_number"]) . '</p>';
            echo '<p><a href="Booking.php?doctor_id=' . htmlspecialchars($row["doctor_id"]) . '"><button class="button">احجز موعد</button></a></p>';
            echo '</div>';
        }
    } else {
        echo '<p style="text-align:center">الدكتور غير متوفر.</p>'; // Message if no details found
    }
    ?>
</body>
</html>
