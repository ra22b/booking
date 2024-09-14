<?php

$is_logged_in = isset($_SESSION['user_id']); // تحقق من وجود الجلسة باستخدام user_id
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>Header</title>
    <style>
        /* تصميم الـ header */
        header {
            width: 100%;
            height: 5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #fdfdfd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0 20px 2px #037272;
            font-size: 1.5rem;
            padding: 0;
            z-index: 1000; /* الهيدر له z-index أعلى */
        }

        header img {
            padding: 0 80px 0 0;
            height: 100%;
            width: 120px;
            object-fit: contain;
        }

        .center {
            display: flex;
            gap: 2rem;
        }

        .center a {
            text-decoration: none;
            color: #207c79;
            transition: font-size 1s;
        }

        .center a:hover {
            font-size: 1.6rem;
            color: #037272;
        }

        .login {
            padding: 0.5rem 1rem;
            background-color: #037272;
            color: #ffffff;
            text-decoration: none;
            font-size: 1.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s, color 0.3s;
        }

        .login:hover {
            background-color: #ffffff;
            color: #037272;
        }

        /* استعلامات الوسائط (Media Queries) */
        /* لأجهزة الهواتف المحمولة */
        @media (max-width: 767px) {
            header {
                flex-direction: row;
                height: auto;
                padding: 1rem;
            }

            header img {
                width: 80px;
                height: auto;
            }

            .center {
                flex-direction: row;
                gap: 1rem;
                align-items: center;
            }

            .login {
                font-size: 1.2rem;
                padding: 0.5rem 1rem;
            }

            .center a {
                font-size: 1.2rem;
            }
        }

        /* لأجهزة الأجهزة اللوحية */
        @media (min-width: 768px) and (max-width: 1024px) {
            header {
                padding: 0 1.5rem;
            }

            .center {
                gap: 1.5rem;
            }

            .login {
                font-size: 1.4rem;
                padding: 0.4rem 0.8rem;
            }

            .center a {
                font-size: 1.3rem;
            }
        }

        /* للشاشات الكبيرة */
        @media (min-width: 1025px) {
            header {
                padding: 0 3rem;
            }

            .login {
                font-size: 1.5rem;
                padding: 0.5rem 1rem;
            }

            .center a {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <?php if ($is_logged_in): ?>
            <a href="logout.php" class="login">تسجيل الخروج</a>
        <?php else: ?>
            <a href="SignIn.php" class="login">تسجيل الدخول</a>
        <?php endif; ?>

        <nav class="center">
            <b><a href="booking_status.php">موعدك</a></b>
            <b><a href="#">موقعنا</a></b>
            <b><a href="#main section">الصفحة الرئيسة</a></b>
        </nav>
        <a href="Home.php"><img src="image/logo_book.png" alt="شعار التطبيق - كتاب"></a>
    </header>
</body>
</html>