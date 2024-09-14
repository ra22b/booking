<?php

    // Get values from the form (GET method)
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $gender = isset($_GET['gender']) ? $_GET['gender'] : '';
    $city = isset($_GET['cities']) ? $_GET['cities'] : '';  // Ensure the form field name is 'cities'
    $specialization = isset($_GET['specialization']) ? $_GET['specialization'] : '';

    // Build dynamic SQL query
    $sql = "SELECT d.doctor_id, d.doctor_name, d.gender, c.city_name, s.specialization_name, d.profile_image, d.working_days
            FROM doctors d
            LEFT JOIN cities c ON d.city_id = c.city_id
            LEFT JOIN specializations s ON d.specialization_id = s.specialization_id
            WHERE 1=1"; // Ensure query is valid even if no filters are applied
    $params = [];

    // Add filters to the SQL query based on user input
    if ($search) {
        $sql .= " AND d.doctor_name LIKE :search";
        $params[':search'] = "%$search%";
    }
    if ($gender) {
        $sql .= " AND d.gender = :gender";
        $params[':gender'] = $gender;
    }
    if ($city) {
        $sql .= " AND c.city_name = :city";
        $params[':city'] = $city;
    }
    if ($specialization) {
        $sql .= " AND s.specialization_name = :specialization";
        $params[':specialization'] = $specialization;
    }

    // If no filters are applied, show 8 random doctors
    if (!$search && !$gender && !$city && !$specialization) {
        $sql .= " ORDER BY RAND() LIMIT 8";
    }

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        // Execute the query with the parameters
        $stmt->execute($params);
        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            // Display doctors' cards
            echo '<div class="card-container">';
            foreach ($results as $row) {
                echo '<div class="card">';
                echo '<img src="Admin/uploads/' . htmlspecialchars($row["profile_image"]) . '" style="width:100%"> ';
                echo '<h1>د. ' . htmlspecialchars($row["doctor_name"]) . '</h1>';
                echo '<p class="title"> <b> التخصص: </b> ' . htmlspecialchars($row["specialization_name"]) . '</p>';
                echo '<p> <b>المدينة: </b>' . htmlspecialchars($row["city_name"]) . '</p>';
                echo '<p> <b> أيام الدوام: </b> ' . htmlspecialchars($row["working_days"]) . '</p>';
                echo '<p><a href="Detilse_doctor.php?doctor_id=' . htmlspecialchars($row["doctor_id"]) . '"><button class="button">تفاصيل أكثر </button></a></p>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            // No results found
            echo "0 results";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
?>
