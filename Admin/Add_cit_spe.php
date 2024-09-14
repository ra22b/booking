<?php
session_start();
include('../db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Add_cit_spe.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>إضافة مدينة أو تخصص</title>
</head>
<body style="text-align: right">
    <!-- Sidebar Start -->
    <?php include('Sidebar.html'); ?>
    <!-- Sidebar End -->

    <!-- Page Content Start -->
    <div style="margin-right:15%">
        <div class="w3-container w3-center fade-in" style="background:#037272; color:white;">
            <h1>إضافة مدينة أو تخصص</h1>
        </div>

        <div class="w3-container">

            <!-- Start Message Error -->
            <div id="error-message" style="color:red; text-align:center; margin: 1em 0;">
                <?php
                if (isset($_GET['message'])) {
                    echo htmlspecialchars($_GET['message']);
                }
                ?>
            </div>
            <!-- End Message Error -->

            <main>
                <div class="forms-container">
                    <!-- Form 1: Add City -->
                    <form action="Add.php" method="post">
                        <div class="mainContent">
                            <div class="content-1">
                                <div class="content">
                                    <!-- City Name -->
                                    <div class="input">
                                        <p class="title">المدينة</p>
                                        <input
                                            type="text"
                                            placeholder="اسم المدينة"
                                            id="city_name"
                                            name="city_name"
                                            max="50"
                                            required
                                        />
                                    </div>
                                    <div class="input submit">
                                        <input type="submit" value="إضافة" name="submit_cit" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- End Form 1 -->

                    <!-- Form 2: Add Specialization -->
                    <form action="Add.php" method="post">
                        <div class="mainContent">
                            <div class="content-2">
                                <div class="content">
                                    <!-- Specialization Name -->
                                    <div class="input">
                                        <p class="title">التخصص</p>
                                        <input
                                            type="text"
                                            placeholder="التخصص"
                                            id="specializations"
                                            name="specializations"
                                            max="50"
                                            required
                                        />
                                    </div>
                                    <div class="input submit">
                                        <input type="submit" value="إضافة" name="submit_spe" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- End Form 2 -->
                </div>
            </main>
        </div>
    </div>
    <!-- Page Content End -->
</body>
</html>
