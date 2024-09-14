<?php 
    // Include database connection and start session
    include("db_connect.php");
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>احجز طبيبك - رئيسة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style_home.css">
</head>

<body>
    <!-- Include header -->
    <?php include('header.php'); ?> 

    <div class="content_main" id="main-section">
        <!-- Left section for the image -->
        <div class="image" id="search">
            <img src="image/home.jpg" alt="Image description">
        </div>

        <!-- Right section for the search and filter options -->
        <div class="search">
            <!-- Search form -->
            <form method="get" class="searchform" id="searchForm">
                <div class="searchbar">
                    <input type="text" class="searchinput" name="search" id="searchInput" placeholder="اسم الطبيب" value="">
                    <button type="submit" class="search-button">بحث</button>
                </div>

                <!-- Filter options -->
                <div class="filter">
                    <!-- Gender filter -->
                    <select name="gender" id="genderSelect">
                        <option value="">الجنس</option>
                        <option value="male">ذكر</option>
                        <option value="female">أنثى</option>
                    </select>

                    <!-- Specialization filter -->
                    <select name="specialization" id="specializationSelect">
                        <option value="">اختر التخصص</option>
                        <?php
                        // Fetch specializations from the database
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

                    <!-- Cities filter -->
                    <select name="cities" id="citiesSelect">
                        <option value="">اختر المدينة</option>
                        <?php
                        // Fetch cities from the database
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
            </form>
        </div>
    </div>

    <script>
        // Reset the form inputs after submission based on URL parameters
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            document.getElementById('searchInput').value = urlParams.get('search') || '';
            document.getElementById('genderSelect').value = urlParams.get('gender') || '';
            document.getElementById('specializationSelect').value = urlParams.get('specialization') || '';
            document.getElementById('citiesSelect').value = urlParams.get('cities') || '';
        };
    </script>

    <!-- Doctor profiles section -->
    <h2 class="title_p2" id="doctors">ملفات تعريف الأطباء</h2>
    <?php include('search.php'); ?>

    <!-- Footer -->
    <?php include('fotter.html'); ?> 
</body>
</html>
